<?php

namespace App\Jobs;

use App\Models\ImportProgress;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessConfigurationImportBatch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $sessionId;

    private $type;

    private $batchData;

    private $config;

    private $startIndex;

    public function __construct($sessionId, $type, $batchData, $config, $startIndex = 0)
    {
        $this->sessionId = $sessionId;
        $this->type = $type;
        $this->batchData = $batchData;
        $this->config = $config;
        $this->startIndex = $startIndex;
    }

    public function handle()
    {
        $progress = ImportProgress::where('session_id', $this->sessionId)->firstOrFail();
        $progress->update(['status' => 'processing']);

        $modelClass = $this->config[0];
        $identityKeys = $this->config[2] ?? [];

        $created = 0;
        $updated = 0;
        $failed = 0;
        $errors = [];

        try {
            \DB::beginTransaction();

            foreach ($this->batchData as $data) {
                $status = $data['_import_status'] ?? 'ready';
                unset($data['_import_status']);

                try {
                    if ($status === 'update' && ! empty($identityKeys)) {
                        $query = $modelClass::query();
                        foreach ($identityKeys as $k) {
                            if (isset($data[$k])) {
                                $query->where($k, $data[$k]);
                            }
                        }

                        $record = $query->first();
                        if ($record) {
                            $record->update($data);
                            $updated++;
                        } else {
                            $modelClass::create($data);
                            $created++;
                        }
                    } else {
                        $query = $modelClass::query();
                        foreach ($data as $k => $v) {
                            $query->where($k, $v);
                        }

                        if (! $query->exists()) {
                            $modelClass::create($data);
                            $created++;
                        }
                    }
                } catch (\Exception $e) {
                    $failed++;
                    $errors[] = 'Row '.($this->startIndex + count($errors) + 1).': '.$e->getMessage();
                    if (count($errors) <= 100) {
                        continue;
                    }
                }
            }

            \DB::commit();

            $progress->update([
                'processed_rows' => $progress->processed_rows + count($this->batchData),
                'created_count' => $progress->created_count + $created,
                'updated_count' => $progress->updated_count + $updated,
                'failed_count' => $progress->failed_count + $failed,
                'errors' => array_merge($progress->errors ?? [], $errors),
            ]);

        } catch (\Exception $e) {
            \DB::rollBack();
            $progress->update([
                'status' => 'failed',
                'errors' => array_merge($progress->errors ?? [], ['Batch error: '.$e->getMessage()]),
            ]);
            throw $e;
        }
    }
}
