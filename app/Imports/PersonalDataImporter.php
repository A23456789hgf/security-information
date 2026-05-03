<?php

namespace App\Imports;

use App\Models\ImportProgress;
use App\Models\PersonalData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;

class PersonalDataImporter implements ToCollection, WithChunkReading, WithStartRow
{
    private $sessionId;
    private $mapping;
    private $mode;
    private $config;
    private $progress;
    private $startRow;

    private $created = 0;
    private $updated = 0;
    private $failed = 0;
    private $errors = [];

    // Caches for lookups
    private $caches = [];

    public function __construct($sessionId, $mapping, $mode, $config, $startRow = 2)
    {
        $this->sessionId = $sessionId;
        $this->mapping = $mapping;
        $this->mode = $mode;
        $this->config = $config;
        $this->startRow = $startRow;
        $this->progress = ImportProgress::where('session_id', $sessionId)->first();
    }

    private function normalizeArabic($str)
    {
        $str = trim((string)$str);
        $str = str_replace(['أ', 'إ', 'آ'], 'ا', $str);
        $str = str_replace('ة', 'ه', $str);
        $str = str_replace('ى', 'ي', $str);
        return $str;
    }

    public function startRow(): int
    {
        return $this->startRow;
    }

    public function collection(Collection $rows)
    {
        $fieldsConfig = $this->config['fields'];
        $identityKeys = $this->config['identity'] ?? ['national_id_number'];

        foreach ($rows as $rowIndex => $row) {
            $rowArray = $row->toArray();
            if (empty(array_filter($rowArray))) continue;

            $rowData = [];
            $rowErrors = [];

            // We need a specific order to resolve hierarchical names (Gov -> Dir -> Sub -> Village)
            $locationFields = [
                'governorate_id', 'directorate_id', 'sub_area_id', 'village_id',
                'place_of_birth_governorate_id', 'place_of_birth_directorate_id', 
                'place_of_birth_sub_area_id', 'place_of_birth_village_id'
            ];

            // First pass: non-location fields
            foreach ($fieldsConfig as $field => $label) {
                if (in_array($field, $locationFields)) continue;

                $colIndex = $this->mapping[$field] ?? null;
                $val = ($colIndex !== null && isset($rowArray[$colIndex])) ? trim((string)$rowArray[$colIndex]) : '';

                if (empty($val)) continue;

                if (str_ends_with($field, '_id')) {
                    $id = $this->resolveId($field, $val, $rowData);
                    if ($id) {
                        $rowData[$field] = $id;
                    } else {
                        $rowErrors[] = "$label غير موجود ($val)";
                    }
                } else {
                    $rowData[$field] = $val;
                }
            }

            // Second pass: location fields in hierarchy
            foreach ($locationFields as $field) {
                if (!isset($fieldsConfig[$field])) continue;

                $colIndex = $this->mapping[$field] ?? null;
                $val = ($colIndex !== null && isset($rowArray[$colIndex])) ? trim((string)$rowArray[$colIndex]) : '';

                if (empty($val)) continue;

                $id = $this->resolveLocationId($field, $val, $rowData);
                if ($id) {
                    $rowData[$field] = $id;
                } else {
                    $label = $fieldsConfig[$field];
                    $rowErrors[] = "$label غير موجود أو غير مرتبط بالهيكل الإداري ($val)";
                }
            }

            if (empty($rowErrors)) {
                try {
                    $this->processRecord($identityKeys, $rowData);
                } catch (\Exception $e) {
                    $this->failed++;
                    if (count($this->errors) < 100) {
                        $this->errors[] = "خطأ حفظ في الصف " . ($rowIndex + $this->startRow) . ": " . $e->getMessage();
                    }
                }
            } else {
                $this->failed++;
                if (count($this->errors) < 100) {
                    $this->errors[] = "الصف " . ($rowIndex + $this->startRow) . ": " . implode(", ", $rowErrors);
                }
            }
        }

        $this->updateProgress();
    }

    private function resolveId($field, $val, &$rowData)
    {
        $tableMapping = [
            'blood_type_id' => ['table' => 'blood_types', 'column' => 'type'],
            'marital_status_id' => ['table' => 'marital_statuses', 'column' => 'status'],
            'qualification_id' => ['table' => 'qualifications', 'column' => 'name'],
            'number_classification_id' => ['table' => 'number_classifications', 'column' => 'classification'],
            'job_status_id' => ['table' => 'job_statuses', 'column' => 'job_status'],
            'rank_id' => ['table' => 'ranks', 'column' => 'rank'],
            'military_district_id' => ['table' => 'military_districts', 'column' => 'name'],
            'division_id' => ['table' => 'divisions', 'column' => 'name'],
            'place_of_birth_country_id' => ['table' => 'countries', 'column' => 'name'],
            'country_id' => ['table' => 'countries', 'column' => 'name'],
            'financial_status_id' => ['table' => 'financial_statuses', 'column' => 'name'],
        ];

        if (!isset($tableMapping[$field])) return null;

        $table = $tableMapping[$field]['table'];
        $column = $tableMapping[$field]['column'];

        if (!isset($this->caches[$table])) {
            $this->caches[$table] = DB::table($table)->get(['id', $column])->mapWithKeys(function($item) use ($column) {
                return [$this->normalizeArabic($item->$column) => $item->id];
            })->toArray();
        }

        $normVal = $this->normalizeArabic($val);
        return $this->caches[$table][$normVal] ?? (is_numeric($val) ? DB::table($table)->where('id', $val)->value('id') : null);
    }

    private function resolveLocationId($field, $val, $rowData)
    {
        $normVal = $this->normalizeArabic($val);
        
        if ($field === 'governorate_id' || $field === 'place_of_birth_governorate_id') {
            if (!isset($this->caches['governorates'])) {
                $this->caches['governorates'] = DB::table('governorates')->get(['id', 'name'])->mapWithKeys(function($i) {
                    return [$this->normalizeArabic($i->name) => $i->id];
                })->toArray();
            }
            return $this->caches['governorates'][$normVal] ?? (is_numeric($val) ? $val : null);
        }

        if ($field === 'directorate_id' || $field === 'place_of_birth_directorate_id') {
            $parentKey = str_contains($field, 'place_of_birth') ? 'place_of_birth_governorate_id' : 'governorate_id';
            $parentId = $rowData[$parentKey] ?? null;

            $rows = DB::table('directorates')->get(['id', 'name', 'governorate_id']);
            foreach ($rows as $d) {
                if ($this->normalizeArabic($d->name) === $normVal || $d->id == $val) {
                    // If parent is known, enforce it; otherwise take best match
                    if (!$parentId || $d->governorate_id == $parentId) return $d->id;
                }
            }
            return null;
        }

        if ($field === 'sub_area_id' || $field === 'place_of_birth_sub_area_id') {
            $parentKey = str_contains($field, 'place_of_birth') ? 'place_of_birth_directorate_id' : 'directorate_id';
            $parentId = $rowData[$parentKey] ?? null;

            $rows = DB::table('sub_areas')->get(['id', 'name', 'directorate_id']);
            foreach ($rows as $s) {
                if ($this->normalizeArabic($s->name) === $normVal || $s->id == $val) {
                    if (!$parentId || $s->directorate_id == $parentId) return $s->id;
                }
            }
            return null;
        }

        if ($field === 'village_id' || $field === 'place_of_birth_village_id') {
            $parentKey = str_contains($field, 'place_of_birth') ? 'place_of_birth_sub_area_id' : 'sub_area_id';
            $parentId = $rowData[$parentKey] ?? null;

            $rows = DB::table('villages')->get(['id', 'name', 'sub_area_id']);
            foreach ($rows as $v) {
                if ($this->normalizeArabic($v->name) === $normVal || $v->id == $val) {
                    if (!$parentId || $v->sub_area_id == $parentId) return $v->id;
                }
            }
            return null;
        }

        return null;
    }

    private function processRecord($identityKeys, $rowData)
    {
        $query = PersonalData::query();
        foreach ($identityKeys as $k) {
            if (isset($rowData[$k])) $query->where($k, $rowData[$k]);
        }

        $record = $query->first();

        if ($this->mode === 'append' && $record) return;
        if ($this->mode === 'update' && !$record) { $this->failed++; return; }

        if ($record) {
            $record->update($rowData);
            $this->updated++;
        } else {
            PersonalData::create($rowData);
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
        if ($this->progress) {
            $this->progress->update([
                'status' => 'completed',
                'processed_rows' => $this->created + $this->updated + $this->failed,
                'created_count' => $this->created,
                'updated_count' => $this->updated,
                'failed_count' => $this->failed,
                'errors' => array_unique($this->errors)
            ]);
        }
    }

    public function chunkSize(): int
    {
        return 500;
    }
}
