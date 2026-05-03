<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;

class DataDifferentiationController extends Controller
{
    /**
     * Get or create current data table
     */
    private function getCurrentTable()
    {
        $tableId = session('current_data_table_id');
        if ($tableId) {
            $table = \App\Models\DataTable::find($tableId);
            if ($table) return $table;
        }
        
        $table = \App\Models\DataTable::where('type', request('origin', 'default'))->latest()->first();
        if ($table) {
            session(['current_data_table_id' => $table->id]);
            return $table;
        }

        return null;
    }

    /**
     * Get or create differentiation results table
     */
    private function getOrCreateResultsTable($primaryTable)
    {
        if (!$primaryTable) {
            return \App\Models\DataTable::create([
                'name' => 'Differentiation Results ' . now()->format('Y-m-d H:i'),
                'type' => 'differentiation-results',
                'column_headers' => []
            ]);
        }

        $resultsTable = \App\Models\DataTable::where('type', 'differentiation-results')
            ->where('parent_table_id', $primaryTable->id)
            ->latest()
            ->first();

        if (!$resultsTable) {
            $resultsTable = \App\Models\DataTable::create([
                'name' => 'Differentiation Results ' . now()->format('Y-m-d H:i'),
                'type' => 'differentiation-results',
                'parent_table_id' => $primaryTable->id,
                'column_headers' => []
            ]);
        }

        return $resultsTable;
    }

    /**
     * Display differentiation page with sidebar
     */
    public function index()
    {
        $table = $this->getCurrentTable();
        $primaryColumns = $table ? $table->column_headers : [];
        $primaryData = $table ? $table->records()->orderBy('sequence')->get()->pluck('data')->toArray() : [];
        
        $resultsTableId = session('differentiation_results_table_id');
        $resultsTable = null;
        if ($resultsTableId) {
            $resultsTable = \App\Models\DataTable::find($resultsTableId);
        }
        
        if ($resultsTable) {
            $secondaryColumns = $resultsTable->column_headers ?? [];
            $secondaryData = $resultsTable->records()->orderBy('sequence')->get()->pluck('data')->toArray();
            $results = $resultsTable->differentiation_results ?? null;
        } else {
            $secondaryColumns = session('secondary_columns', []);
            $secondaryData = session('secondary_data', []);
            $results = session('differentiation_results', null);
        }
        
        return view('data.differentiation.index', compact(
            'primaryColumns', 
            'primaryData', 
            'secondaryColumns', 
            'secondaryData', 
            'results'
        ));
    }

    /**
     * Perform differentiation analysis
     */
    public function analyze(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv'
        ]);

        $file = $request->file('excel_file');
        $spreadsheet = IOFactory::load($file->getPathname());
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        $secondaryHeaders = array_shift($rows);
        $secondaryData = $rows;

        $table = $this->getCurrentTable();
        $primaryData = $table ? $table->records()->orderBy('sequence')->get()->pluck('data')->toArray() : [];
        $primaryColumns = $table ? $table->column_headers : [];

        $results = $this->performDifferentiation($primaryData, $secondaryData, $primaryColumns);
        
        $resultsTable = $this->getOrCreateResultsTable($table);
        $resultsTable->update([
            'column_headers' => $secondaryHeaders,
            'differentiation_results' => $results
        ]);

        $resultsTable->records()->delete();
        foreach ($secondaryData as $index => $row) {
            $resultsTable->records()->create([
                'data' => $row,
                'sequence' => $index
            ]);
        }

        session(['differentiation_results' => $results]);
        session(['differentiation_results_table_id' => $resultsTable->id]);
        session(['secondary_data' => $secondaryData]);
        session(['secondary_columns' => $secondaryHeaders]);

        return response()->json([
            'success' => true,
            'results' => $results,
            'secondary_count' => count($secondaryData)
        ]);
    }

    /**
     * Perform all differentiation analyses
     */
    private function performDifferentiation($primaryData, $secondaryData, $columns)
    {
        $results = [
            'one_to_one' => ['label' => 'واحد لواحد', 'count' => 0, 'matches' => []],
            'two_total' => ['label' => 'مجموع اثنين', 'count' => 0, 'matches' => []],
            'three_total' => ['label' => 'مجموع ثلاثة', 'count' => 0, 'matches' => []],
            'four_total' => ['label' => 'مجموع أربعة', 'count' => 0, 'matches' => []],
            'five_total' => ['label' => 'مجموع خمسة', 'count' => 0, 'matches' => []],
            'six_total' => ['label' => 'مجموع ستة', 'count' => 0, 'matches' => []],
            'letter_difference' => ['label' => 'اختلاف حرفي', 'count' => 0, 'matches' => []],
            'total_variations' => ['label' => 'مجموع مع تباينات', 'count' => 0, 'matches' => []],
            'last' => ['label' => 'الأخير', 'count' => 0, 'matches' => []],
            'single_last_diff' => ['label' => 'مفرد مع اختلاف أخير', 'count' => 0, 'matches' => []],
            'double_last_diff' => ['label' => 'مزدوج مع اختلاف أخير', 'count' => 0, 'matches' => []],
            'triple_last_diff' => ['label' => 'ثلاثي مع اختلاف أخير', 'count' => 0, 'matches' => []],
            'quadruple_last_diff' => ['label' => 'رباعي مع اختلاف أخير', 'count' => 0, 'matches' => []],
            'centruple_last_diff' => ['label' => 'خماسي مع اختلاف أخير', 'count' => 0, 'matches' => []],
            'sextuple_last_diff' => ['label' => 'سداسي مع اختلاف أخير', 'count' => 0, 'matches' => []],
            'net' => ['label' => 'صافي', 'count' => 0, 'matches' => []],
        ];

        foreach ($secondaryData as $secIndex => $secRow) {
            foreach ($primaryData as $priIndex => $priRow) {
                $matchCount = $this->countMatchingFields($priRow, $secRow);
                $totalFields = min(count($priRow), count($secRow));
                $lastFieldMatch = $this->checkLastFieldMatch($priRow, $secRow);
                $letterDiff = $this->calculateLetterDifference($priRow, $secRow);

                // One-to-one: All fields match exactly
                if ($matchCount === $totalFields && $totalFields > 0) {
                    $results['one_to_one']['count']++;
                    $results['one_to_one']['matches'][] = ['primary' => $priIndex, 'secondary' => $secIndex];
                }

                // N-total: Exactly N fields match
                if ($matchCount === 2) {
                    $results['two_total']['count']++;
                    $results['two_total']['matches'][] = ['primary' => $priIndex, 'secondary' => $secIndex];
                }
                if ($matchCount === 3) {
                    $results['three_total']['count']++;
                    $results['three_total']['matches'][] = ['primary' => $priIndex, 'secondary' => $secIndex];
                }
                if ($matchCount === 4) {
                    $results['four_total']['count']++;
                    $results['four_total']['matches'][] = ['primary' => $priIndex, 'secondary' => $secIndex];
                }
                if ($matchCount === 5) {
                    $results['five_total']['count']++;
                    $results['five_total']['matches'][] = ['primary' => $priIndex, 'secondary' => $secIndex];
                }
                if ($matchCount === 6) {
                    $results['six_total']['count']++;
                    $results['six_total']['matches'][] = ['primary' => $priIndex, 'secondary' => $secIndex];
                }

                // Letter difference: One character different in any field
                if ($letterDiff === 1) {
                    $results['letter_difference']['count']++;
                    $results['letter_difference']['matches'][] = ['primary' => $priIndex, 'secondary' => $secIndex];
                }

                // Total with variations: Most fields match but with small differences
                if ($matchCount >= $totalFields - 2 && $matchCount < $totalFields && $letterDiff <= 3) {
                    $results['total_variations']['count']++;
                    $results['total_variations']['matches'][] = ['primary' => $priIndex, 'secondary' => $secIndex];
                }

                // Last: Last field matches
                if ($lastFieldMatch) {
                    $results['last']['count']++;
                    $results['last']['matches'][] = ['primary' => $priIndex, 'secondary' => $secIndex];
                }

                // With last difference variants
                if (!$lastFieldMatch) {
                    if ($matchCount === 1) {
                        $results['single_last_diff']['count']++;
                        $results['single_last_diff']['matches'][] = ['primary' => $priIndex, 'secondary' => $secIndex];
                    }
                    if ($matchCount === 2) {
                        $results['double_last_diff']['count']++;
                        $results['double_last_diff']['matches'][] = ['primary' => $priIndex, 'secondary' => $secIndex];
                    }
                    if ($matchCount === 3) {
                        $results['triple_last_diff']['count']++;
                        $results['triple_last_diff']['matches'][] = ['primary' => $priIndex, 'secondary' => $secIndex];
                    }
                    if ($matchCount === 4) {
                        $results['quadruple_last_diff']['count']++;
                        $results['quadruple_last_diff']['matches'][] = ['primary' => $priIndex, 'secondary' => $secIndex];
                    }
                    if ($matchCount === 5) {
                        $results['centruple_last_diff']['count']++;
                        $results['centruple_last_diff']['matches'][] = ['primary' => $priIndex, 'secondary' => $secIndex];
                    }
                    if ($matchCount === 6) {
                        $results['sextuple_last_diff']['count']++;
                        $results['sextuple_last_diff']['matches'][] = ['primary' => $priIndex, 'secondary' => $secIndex];
                    }
                }
            }
        }

        // Net: Records in secondary not matching any in primary
        $matchedSecondary = [];
        foreach ($results as $key => $result) {
            if ($key !== 'net') {
                foreach ($result['matches'] as $match) {
                    $matchedSecondary[$match['secondary']] = true;
                }
            }
        }
        
        foreach ($secondaryData as $secIndex => $secRow) {
            if (!isset($matchedSecondary[$secIndex])) {
                $results['net']['count']++;
                $results['net']['matches'][] = ['secondary' => $secIndex];
            }
        }

        return $results;
    }

    /**
     * Count matching fields between two rows
     */
    private function countMatchingFields($row1, $row2)
    {
        $count = 0;
        $minLength = min(count($row1), count($row2));
        
        for ($i = 0; $i < $minLength; $i++) {
            if ($this->normalizeValue($row1[$i]) === $this->normalizeValue($row2[$i])) {
                $count++;
            }
        }
        
        return $count;
    }

    /**
     * Check if last fields match
     */
    private function checkLastFieldMatch($row1, $row2)
    {
        if (empty($row1) || empty($row2)) return false;
        
        $last1 = $this->normalizeValue(end($row1));
        $last2 = $this->normalizeValue(end($row2));
        
        return $last1 === $last2;
    }

    /**
     * Calculate total letter difference between rows
     */
    private function calculateLetterDifference($row1, $row2)
    {
        $totalDiff = 0;
        $minLength = min(count($row1), count($row2));
        
        for ($i = 0; $i < $minLength; $i++) {
            $val1 = $this->normalizeValue($row1[$i]);
            $val2 = $this->normalizeValue($row2[$i]);
            $totalDiff += levenshtein($val1, $val2);
        }
        
        return $totalDiff;
    }

    /**
     * Normalize value for comparison
     */
    private function normalizeValue($value)
    {
        if ($value === null) return '';
        return mb_strtolower(trim((string)$value));
    }

    /**
     * Get results via AJAX
     */
    public function getResults()
    {
        $resultsTableId = session('differentiation_results_table_id');
        if ($resultsTableId) {
            $resultsTable = \App\Models\DataTable::find($resultsTableId);
            if ($resultsTable && $resultsTable->differentiation_results) {
                return response()->json([
                    'results' => $resultsTable->differentiation_results
                ]);
            }
        }
        
        return response()->json([
            'results' => session('differentiation_results', null)
        ]);
    }

    /**
     * Get matched records detail
     */
    public function getMatchDetail(Request $request)
    {
        $type = $request->input('type');
        
        $resultsTableId = session('differentiation_results_table_id');
        $resultsTable = null;
        if ($resultsTableId) {
            $resultsTable = \App\Models\DataTable::find($resultsTableId);
        }
        
        if ($resultsTable) {
            $secondaryData = $resultsTable->records()->orderBy('sequence')->get()->pluck('data')->toArray();
            $secondaryColumns = $resultsTable->column_headers ?? [];
            $results = $resultsTable->differentiation_results ?? [];
        } else {
            $secondaryData = session('secondary_data', []);
            $secondaryColumns = session('secondary_columns', []);
            $results = session('differentiation_results', []);
        }
        
        $table = $this->getCurrentTable();
        $primaryData = $table ? $table->records()->orderBy('sequence')->get()->pluck('data')->toArray() : [];
        $primaryColumns = $table ? $table->column_headers : [];
        
        $details = [];
        
        if (isset($results[$type])) {
            foreach ($results[$type]['matches'] as $match) {
                $detail = [
                    'primary' => isset($match['primary']) ? $primaryData[$match['primary']] ?? null : null,
                    'secondary' => isset($match['secondary']) ? $secondaryData[$match['secondary']] ?? null : null,
                    'secondary_index' => $match['secondary'] ?? null
                ];
                $details[] = $detail;
            }
        }

        return response()->json([
            'type' => $type,
            'label' => $results[$type]['label'] ?? '',
            'details' => $details,
            'primary_columns' => $primaryColumns,
            'secondary_columns' => $secondaryColumns
        ]);
    }

    /**
     * Integrate all secondary data into primary data
     */
    public function integrateAll()
    {
        $table = $this->getCurrentTable();
        $secondaryData = session('secondary_data', []);
        
        if (empty($secondaryData)) {
            return response()->json(['success' => false, 'message' => 'لا توجد بيانات مرجعية للدمج']);
        }

        if (!$table) {
            $table = \App\Models\DataTable::create([
                'name' => 'Integrated Data ' . now()->format('Y-m-d H:i'),
                'type' => request('origin', 'default'),
                'column_headers' => session('secondary_columns', [])
            ]);
            session(['current_data_table_id' => $table->id]);
        }

        $lastSequence = $table->records()->max('sequence') ?? -1;

        foreach ($secondaryData as $index => $row) {
            $table->records()->create([
                'data' => $row,
                'sequence' => $lastSequence + $index + 1
            ]);
        }

        $primaryData = $table->records()->orderBy('sequence')->get()->pluck('data')->toArray();
        
        session(['data_rows' => $primaryData]);

        return response()->json(['success' => true, 'message' => 'تم دمج جميع البيانات بنجاح']);
    }

    /**
     * Integrate specific records into primary data
     */
    public function integrateSelected(Request $request)
    {
        $indices = $request->input('indices', []);
        $secondaryData = session('secondary_data', []);
        
        $table = $this->getCurrentTable();
        
        if (!$table) {
            $table = \App\Models\DataTable::create([
                'name' => 'Integrated Data ' . now()->format('Y-m-d H:i'),
                'type' => request('origin', 'default'),
                'column_headers' => session('secondary_columns', [])
            ]);
            session(['current_data_table_id' => $table->id]);
        }

        $lastSequence = $table->records()->max('sequence') ?? -1;
        $addedCount = 0;

        foreach ($indices as $i => $index) {
            if (isset($secondaryData[$index])) {
                $table->records()->create([
                    'data' => $secondaryData[$index],
                    'sequence' => $lastSequence + $addedCount + 1
                ]);
                $addedCount++;
            }
        }

        $primaryData = $table->records()->orderBy('sequence')->get()->pluck('data')->toArray();
        
        session(['data_rows' => $primaryData]);
        return response()->json(['success' => true, 'message' => 'تم دمج السجلات المختارة بنجاح']);
    }

    /**
     * Add a column to secondary data
     */
    public function addSecondaryColumn(Request $request)
    {
        $name = $request->input('name', 'عمود جديد');
        $columns = session('secondary_columns', []);
        $data = session('secondary_data', []);
        
        $columns[] = $name;
        foreach ($data as &$row) {
            $row[] = '';
        }
        
        $resultsTableId = session('differentiation_results_table_id');
        if ($resultsTableId) {
            $resultsTable = \App\Models\DataTable::find($resultsTableId);
            if ($resultsTable) {
                $resultsTable->update(['column_headers' => $columns]);
                foreach ($resultsTable->records()->orderBy('sequence')->get() as $i => $record) {
                    $rowData = $record->data;
                    $rowData[] = '';
                    $record->update(['data' => $rowData]);
                }
            }
        }
        
        session(['secondary_columns' => $columns]);
        session(['secondary_data' => $data]);
        
        return response()->json(['success' => true]);
    }
}
