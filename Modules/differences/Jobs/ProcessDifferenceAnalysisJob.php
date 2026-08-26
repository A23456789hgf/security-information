<?php

namespace Modules\Differences\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Modules\Differences\Models\DifferenceAnalysis;
use Modules\Differences\Models\DifferenceAnalysisResult;
use Modules\Differences\Models\DifferenceAnalysisSetting;
use Modules\Differences\Imports\ExcelImporter;
use Modules\Differences\Services\DynamicRegistryService;
use Modules\Differences\Services\AnalysisLogService;
use Modules\Differences\Engines\MatchingEngine;

class ProcessDifferenceAnalysisJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $analysisId;
    protected $filePath;
    protected $columnMappings;

    /**
     * Create a new job instance.
     */
    public function __construct(int $analysisId, string $filePath, array $columnMappings)
    {
        $this->analysisId = $analysisId;
        $this->filePath = $filePath;
        $this->columnMappings = $columnMappings; // maps Excel headers to standard types: name, id, phone, address
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $analysis = DifferenceAnalysis::find($this->analysisId);
        if (!$analysis) {
            return;
        }

        $analysis->update([
            'status' => 'processing',
            'progress' => 5
        ]);

        AnalysisLogService::info($this->analysisId, 'بدء عملية التحليل لملف البيانات الشخصية والمطابقة', [
            'filePath' => $this->filePath,
            'mappings' => $this->columnMappings
        ]);

        try {
            // 1. Parse Excel headers and total row count
            $importer = new ExcelImporter();
            $fileData = $importer->getHeaderAndTotalRows($this->filePath);
            $totalRows = $fileData['total_rows'];

            $analysis->update([
                'total_rows' => $totalRows,
                'progress' => 10
            ]);

            AnalysisLogService::info($this->analysisId, "تم التعرف على إجمالي عدد صفوف الملف المستورد: $totalRows صف.");

            if ($totalRows === 0) {
                $analysis->update(['status' => 'completed', 'progress' => 100]);
                AnalysisLogService::warning($this->analysisId, 'الملف المستورد فارغ ولا يحتوي على أي سجلات.');
                return;
            }

            // 2. Pre-load all searchable system records from the Registry into memory (using lazy collection stream)
            AnalysisLogService::info($this->analysisId, 'جاري جلب وفهرسة سجلات النظام للمطابقة الديناميكية...');
            $registry = new DynamicRegistryService();
            $systemRecords = [];
            
            // Loop through the generator to cache records in memory
            foreach ($registry->streamSystemRecords() as $record) {
                $systemRecords[] = $record;
            }

            $sysCount = count($systemRecords);
            AnalysisLogService::info($this->analysisId, "تم فهرست $sysCount سجلاً بنجاح من كافة جداول النظام والصفحات النشطة.");

            // 3. Initialize matching engine
            $engine = new MatchingEngine();
            $threshold = $engine->getThreshold();

            // Stats counters
            $stats = [
                'matches' => 0,
                'similarities' => 0,
                'duplicates' => 0,
                'not_found' => 0
            ];

            // 4. Read spreadsheet chunks of 100 rows for processing
            $chunkSize = 100;
            $processedCount = 0;

            for ($startRow = 2; $startRow <= ($totalRows + 1); $startRow += $chunkSize) {
                // Read next chunk
                $chunkRows = $importer->readChunk($this->filePath, $startRow, $chunkSize);
                
                foreach ($chunkRows as $row) {
                    $processedCount++;
                    
                    // Map this excel row's columns to standard types
                    $mappedExternal = [
                        'name' => null,
                        'id' => null,
                        'phone' => null,
                        'address' => null,
                    ];
                    
                    foreach ($this->columnMappings as $excelHeader => $stdType) {
                        if (isset($row[$excelHeader])) {
                            $mappedExternal[$stdType] = $row[$excelHeader];
                        }
                    }

                    $primaryName = $mappedExternal['name'] ?? '';

                    if (empty($primaryName)) {
                        // Skip rows that don't have a name
                        $stats['not_found']++;
                        continue;
                    }

                    // Scan cached system records to find matches
                    $allRowMatches = [];
                    foreach ($systemRecords as $sysRec) {
                        // Map system record structure
                        $mappedSystem = [
                            'name' => $sysRec['name'] ?? null,
                            'id' => $sysRec['id'] ?? null,
                            'phone' => $sysRec['phone'] ?? null,
                            'address' => $sysRec['address'] ?? null,
                        ];

                        $matchResult = $engine->match($mappedExternal, $mappedSystem);
                        
                        if ($matchResult['status'] !== 'not_found' && $matchResult['percentage'] >= $threshold) {
                            $allRowMatches[] = [
                                'sys_record' => $sysRec,
                                'status' => $matchResult['status'],
                                'percentage' => $matchResult['percentage'],
                                'calculations' => $matchResult['calculations'],
                                'triggered_rule' => $matchResult['triggered_rule']
                            ];
                        }
                    }

                    // Classify the final match status for this external row
                    $matchCount = count($allRowMatches);
                    $finalStatus = 'not_found';
                    $bestMatch = null;
                    $similarityPercentage = 0.00;

                    if ($matchCount === 0) {
                        $finalStatus = 'not_found';
                        $stats['not_found']++;
                    } elseif ($matchCount === 1) {
                        $bestMatch = $allRowMatches[0];
                        $finalStatus = $bestMatch['status'];
                        $similarityPercentage = $bestMatch['percentage'];
                        if ($finalStatus === 'match') {
                            $stats['matches']++;
                        } else {
                            $stats['similarities']++;
                        }
                    } else {
                        // Sort by similarity descending
                        usort($allRowMatches, function($a, $b) {
                            return $b['percentage'] <=> $a['percentage'];
                        });
                        $bestMatch = $allRowMatches[0];
                        // If multiple records match, classify as a Duplicate (Orange)!
                        $finalStatus = 'duplicate';
                        $similarityPercentage = $bestMatch['percentage'];
                        $stats['duplicates']++;
                    }

                    // Save result record
                    DifferenceAnalysisResult::create([
                        'analysis_id' => $this->analysisId,
                        'external_name' => $primaryName,
                        'external_row_data' => $row,
                        'status' => $finalStatus,
                        'similarity_percentage' => $similarityPercentage,
                        'matching_name' => $bestMatch ? ($bestMatch['sys_record']['name'] ?? null) : null,
                        'source_target_id' => $bestMatch ? ($bestMatch['sys_record']['_source_target_id'] ?? null) : null,
                        'source_target_name' => $bestMatch ? ($bestMatch['sys_record']['_source_target_name'] ?? null) : null,
                        'source_table' => $bestMatch ? ($bestMatch['sys_record']['_source_table'] ?? null) : null,
                        'source_page' => $bestMatch ? ($bestMatch['sys_record']['_source_page'] ?? null) : null,
                        'source_page_type' => $bestMatch ? ($bestMatch['sys_record']['_source_page_type'] ?? null) : null,
                        'occurrences_count' => $matchCount,
                        'record_status' => $bestMatch ? ($bestMatch['sys_record']['_record_status'] ?? null) : null,
                        'original_record_id' => $bestMatch ? ($bestMatch['sys_record']['_original_record_id'] ?? null) : null,
                        'original_record_data' => $bestMatch ? ($bestMatch['sys_record']['_original_record_data'] ?? null) : null,
                        'all_matches' => $allRowMatches,
                        'matching_details' => $bestMatch ? ($bestMatch['calculations'] ?? null) : null
                    ]);
                }

                // Calculate progress
                $progressPercent = min(95, 10 + (int)(($processedCount / $totalRows) * 85));
                $analysis->update([
                    'processed_rows' => $processedCount,
                    'progress' => $progressPercent,
                    'stats' => $stats
                ]);
            }

            // Completed!
            $analysis->update([
                'status' => 'completed',
                'progress' => 100,
                'processed_rows' => $totalRows,
                'stats' => $stats
            ]);

            AnalysisLogService::info($this->analysisId, 'تم الانتهاء من عملية المطابقة وتحليل التمايز بنجاح وتخزين كافة النتائج.', [
                'stats' => $stats
            ]);

        } catch (\Exception $e) {
            $analysis->update([
                'status' => 'failed',
                'progress' => 100
            ]);

            AnalysisLogService::error($this->analysisId, 'حدث خطأ غير متوقع أثناء معالجة المطابقة في الخلفية: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
