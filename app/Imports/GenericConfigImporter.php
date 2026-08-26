<?php

namespace App\Imports;

use App\Models\ImportProgress;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Row;

class GenericConfigImporter implements OnEachRow, WithChunkReading, WithStartRow
{
    private $sessionId;

    private $mapping;

    private $mode;

    private $config;

    private $startRow;

    protected function normalizeArabic($str)
    {
        $str = trim((string) $str);
        $str = str_replace(['أ', 'إ', 'آ'], 'ا', $str);
        $str = str_replace('ة', 'ه', $str);
        $str = str_replace('ى', 'ي', $str);

        return $str;
    }

    private $created = 0;

    private $updated = 0;

    private $failed = 0;

    private $errors = [];

    // Cache for location lookups
    private $governorates = null;

    private $directorates = null;

    public function __construct($sessionId, $mapping, $mode, $config, $startRow = 2)
    {
        $this->sessionId = $sessionId;
        $this->mapping = $mapping;
        $this->mode = $mode;
        $this->config = $config;
        $this->startRow = $startRow;
        $this->progress = ImportProgress::where('session_id', $sessionId)->first();
    }

    public function startRow(): int
    {
        return $this->startRow;
    }

    public function onRow(Row $row)
    {
        $rowIndex = $row->getIndex();
        $rowArray = $row->toArray();

        // Check if row is entirely empty
        if (empty(array_filter($rowArray))) {
            return;
        }

        $modelClass = $this->config['model'];
        $fieldsConfig = $this->config['fields'];
        $identityKeys = $this->config['identity'] ?? [];

        $rowData = [];
        $rowErrors = [];

        foreach ($fieldsConfig as $field => $label) {
            $colIndex = $this->mapping[$field] ?? null;
            $val = ($colIndex !== null && isset($rowArray[$colIndex])) ? trim((string) $rowArray[$colIndex]) : '';

            if (empty($val)) {
                $rowErrors[] = "الحقل $label مفقود في الصف ".$rowIndex;

                continue;
            }

            // Resolve IDs if field ends in _id
            if (str_ends_with($field, '_id')) {
                $id = $this->resolveId($field, $val, $rowData, $label);
                if ($id) {
                    $rowData[$field] = $id;
                } else {
                    $rowErrors[] = "$label غير موجود ($val) في الصف ".$rowIndex;
                }
            } else {
                $rowData[$field] = $val;
            }
        }

        if (empty($rowErrors)) {
            try {
                $this->processRecord($modelClass, $identityKeys, $rowData);
            } catch (\Exception $e) {
                $this->failed++;
                if (count($this->errors) < 100) {
                    $this->errors[] = "خطأ حفظ في الصف $rowIndex: ".$e->getMessage();
                }
            }
        } else {
            $this->failed++;
            if (count($this->errors) < 100) {
                $this->errors[] = implode(', ', $rowErrors);
            }
        }

        // Update progress every 50 rows
        if ($rowIndex % 50 === 0) {
            $this->updateProgress();
        }
    }

    private function resolveId($field, $val, $rowData, $label)
    {
        $normVal = $this->normalizeArabic($val);

        // Simple caching for common lookups
        if ($field === 'governorate_id') {
            if ($this->governorates === null) {
                $this->governorates = DB::table('governorates')->get(['id', 'name']);
            }
            foreach ($this->governorates as $g) {
                if ($this->normalizeArabic($g->name) === $normVal || $g->id == $val) {
                    return $g->id;
                }
            }

            return null;
        }

        if ($field === 'directorate_id') {
            if ($this->directorates === null) {
                $this->directorates = DB::table('directorates')->get(['id', 'name', 'governorate_id']);
            }

            foreach ($this->directorates as $d) {
                if ($this->normalizeArabic($d->name) === $normVal || $d->id == $val) {
                    if (isset($rowData['governorate_id']) && $d->governorate_id == $rowData['governorate_id']) {
                        return $d->id;
                    } elseif (! isset($rowData['governorate_id'])) {
                        return $d->id;
                    }
                }
            }

            return null;
        }

        // Generic lookup for others
        $relationTable = Str::plural(str_replace('_id', '', $field));
        if ($field === 'military_district_id') {
            $relationTable = 'military_districts';
        }

        $items = DB::table($relationTable)->get(['id', 'name']);
        foreach ($items as $item) {
            if ($this->normalizeArabic($item->name) === $normVal || $item->id == $val) {
                return $item->id;
            }
        }

        return is_numeric($val) ? $val : null;
    }

    private function processRecord($modelClass, $identityKeys, $rowData)
    {
        $query = $modelClass::query();
        if (! empty($identityKeys)) {
            foreach ($identityKeys as $k) {
                if (isset($rowData[$k])) {
                    $query->where($k, $rowData[$k]);
                }
            }
        } else {
            foreach ($rowData as $k => $v) {
                $query->where($k, $v);
            }
        }

        $record = $query->first();

        if ($this->mode === 'append' && $record) {
            // Skip
            return;
        }

        if ($this->mode === 'update' && ! $record) {
            $this->failed++;

            return;
        }

        if ($record) {
            $record->update($rowData);
            $this->updated++;
        } else {
            $modelClass::create($rowData);
            $this->created++;
        }
    }

    private function updateProgress()
    {
        if ($this->progress) {
            $this->progress->update([
                'processed_rows' => $this->created + $this->updated + $this->failed,
                'created_count' => $this->created,
                'updated_count' => $this->updated,
                'failed_count' => $this->failed,
            ]);
        }
    }

    public function __destruct()
    {
        // Final update
        if ($this->progress) {
            $this->progress->update([
                'status' => $this->failed > 0 && ($this->created + $this->updated) == 0 ? 'failed' : 'completed',
                'processed_rows' => $this->created + $this->updated + $this->failed,
                'created_count' => $this->created,
                'updated_count' => $this->updated,
                'failed_count' => $this->failed,
                'errors' => array_unique($this->errors),
            ]);
        }
    }

    public function chunkSize(): int
    {
        return 200;
    }
}
