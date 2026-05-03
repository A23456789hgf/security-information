<?php

namespace App\Jobs;

use App\Models\ImportProgress;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class ProcessConfigurationImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 3600;

    private $sessionId;
    private $filePath;
    private $mapping;
    private $mode;
    private $config;
    private $startRow;
    private $importerClass;

    /**
     * @param string $sessionId
     * @param string $filePath
     * @param array $mapping
     * @param string $mode
     * @param array $config [model, fields, identity, routeName]
     * @param int $startRow
     * @param string|null $importerClass
     */
    public function __construct($sessionId, $filePath, $mapping, $mode, $config, $startRow = 2, $importerClass = null)
    {
        $this->sessionId = $sessionId;
        $this->filePath = $filePath;
        $this->mapping = $mapping;
        $this->mode = $mode;
        $this->config = $config;
        $this->startRow = $startRow;
        $this->importerClass = $importerClass ?? \App\Imports\GenericConfigImporter::class;
    }

    public function handle()
    {
        $progress = ImportProgress::where('session_id', $this->sessionId)->first();
        if (!$progress) return;

        $progress->update(['status' => 'processing']);

        $fullPath = storage_path('app/' . $this->filePath);
        if (!file_exists($fullPath)) {
            $progress->update(['status' => 'failed', 'errors' => ['الملف غير موجود']]);
            return;
        }

        try {
            // Memory efficient import using chunks
            $importer = new $this->importerClass(
                $this->sessionId, 
                $this->mapping, 
                $this->mode, 
                $this->config,
                $this->startRow
            );

            Excel::import($importer, $fullPath);

            // The importer updates progress and handles the file cleanup if needed
            // However, we should still ensure the file is deleted here if the importer doesn't finish cleanly
            if (file_exists($fullPath)) {
                @unlink($fullPath);
            }

        } catch (\Exception $e) {
            $progress->update([
                'status' => 'failed',
                'errors' => ["خطأ النظام: " . $e->getMessage()]
            ]);
        }
    }
}
