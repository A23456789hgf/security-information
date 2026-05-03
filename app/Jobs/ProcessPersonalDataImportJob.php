<?php

namespace App\Jobs;

use App\Models\ImportProgress;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;

class ProcessPersonalDataImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 7200; // 2 hours for large personal data sets

    private $sessionId;
    private $filePath;
    private $mapping;
    private $mode;
    private $config;
    private $startRow;

    /**
     * @param string $sessionId
     * @param string $filePath
     * @param array $mapping
     * @param string $mode
     * @param array $config [model, fields, identity, routeName]
     * @param int $startRow
     */
    public function __construct($sessionId, $filePath, $mapping, $mode, $config, $startRow = 2)
    {
        $this->sessionId = $sessionId;
        $this->filePath = $filePath;
        $this->mapping = $mapping;
        $this->mode = $mode;
        $this->config = $config;
        $this->startRow = $startRow;
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
            Excel::import(
                new \App\Imports\PersonalDataImporter(
                    $this->sessionId, 
                    $this->mapping, 
                    $this->mode, 
                    $this->config,
                    $this->startRow
                ), 
                $fullPath
            );

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
