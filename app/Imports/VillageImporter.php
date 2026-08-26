<?php

namespace App\Imports;

use App\Models\Directorate;
use App\Models\Governorate;
use App\Models\ImportProgress;
use App\Models\SubArea;
use App\Models\Village;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;

class VillageImporter implements ToCollection, WithChunkReading, WithStartRow
{
    private $sessionId;

    private $mapping;

    private $mode;

    private $progress;

    private $startRow;

    // Caches
    private $governorateCache = [];

    private $directorateCache = [];

    private $subAreaCache = [];

    private $villageCache = [];

    private function normalizeArabic($str)
    {
        $str = trim((string) $str);
        $str = str_replace(['أ', 'إ', 'آ'], 'ا', $str);
        $str = str_replace('ة', 'ه', $str);
        $str = str_replace('ى', 'ي', $str);

        return $str;
    }

    public function __construct($sessionId, $mapping, $mode, $config = [], $startRow = 2)
    {
        $this->sessionId = $sessionId;
        $this->mapping = $mapping;
        $this->mode = $mode;
        $this->startRow = $startRow;
        $this->progress = ImportProgress::where('session_id', $sessionId)->first();
    }

    public function startRow(): int
    {
        return $this->startRow ?? 2;
    }

    public function collection(Collection $rows)
    {
        $this->preloadCaches($rows);

        $processed = 0;
        $created = 0;
        $updated = 0;
        $failed = 0;
        $errors = [];

        $toUpsert = [];
        $skipped = 0;

        foreach ($rows as $row) {
            $rowArray = $row->toArray();
            if (empty(array_filter($rowArray))) {
                continue;
            }

            $data = $this->mapRow($rowArray);
            if (isset($data['error'])) {
                $failed++;
                if (count($errors) < 100) {
                    $errors[] = $data['error'];
                }

                continue;
            }

            // Check existence in cache
            $cacheKey = $data['cache_key'];
            $exists = isset($this->villageCache[$cacheKey]);

            if ($this->mode === 'append' && $exists) {
                $skipped++;

                continue;
            }

            if ($exists) {
                $updated++;
            } else {
                $created++;
                // Mark as seen in this chunk to prevent internal duplicates
                $this->villageCache[$cacheKey] = true;
            }

            $upsertData = $data;
            unset($upsertData['cache_key']);
            $toUpsert[] = array_merge($upsertData, [
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (! empty($toUpsert)) {
            DB::transaction(function () use ($toUpsert) {
                // Bulk Upsert: 1 query for 500 rows!
                // We unique on [sub_area_id, name] as per DB constraint
                Village::upsert($toUpsert, ['sub_area_id', 'name'], ['governorate_id', 'directorate_id', 'updated_at']);
            });
        }

        $this->updateProgress($created, $updated, $failed, $errors);
    }

    private function preloadCaches(Collection $rows)
    {
        // For Village import, we need Gov, Dir, and SubArea
        $govValues = [];
        $dirValues = [];
        $subAreaValues = [];

        foreach ($rows as $row) {
            $rowArray = $row->toArray();
            if (isset($this->mapping['governorate_id'])) {
                $val = $rowArray[$this->mapping['governorate_id']] ?? null;
                if ($val) {
                    $govValues[] = trim((string) $val);
                }
            }
            if (isset($this->mapping['directorate_id'])) {
                $val = $rowArray[$this->mapping['directorate_id']] ?? null;
                if ($val) {
                    $dirValues[] = trim((string) $val);
                }
            }
            if (isset($this->mapping['sub_area_id'])) {
                $val = $rowArray[$this->mapping['sub_area_id']] ?? null;
                if ($val) {
                    $subAreaValues[] = trim((string) $val);
                }
            }
        }

        $govValues = array_unique(array_filter($govValues));
        $dirValues = array_unique(array_filter($dirValues));
        $subAreaValues = array_unique(array_filter($subAreaValues));

        // Separate names and IDs
        $govNames = array_filter($govValues, fn ($v) => ! is_numeric($v));
        $govIds = array_filter($govValues, fn ($v) => is_numeric($v));

        $dirNames = array_filter($dirValues, fn ($v) => ! is_numeric($v));
        $dirIds = array_filter($dirValues, fn ($v) => is_numeric($v));

        $subAreaNames = array_filter($subAreaValues, fn ($v) => ! is_numeric($v));
        $subAreaIdsFromExcel = array_filter($subAreaValues, fn ($v) => is_numeric($v));

        if (! empty($govValues)) {
            $this->governorateCache = [];
            foreach (Governorate::whereIn('name', $govNames)->orWhereIn('id', $govIds)->get() as $gov) {
                $this->governorateCache[$this->normalizeArabic($gov->name)] = $gov->id;
                $this->governorateCache[$gov->id] = $gov->id;
            }
        }

        if (! empty($dirValues)) {
            $this->directorateCache = Directorate::whereIn('name', $dirNames)
                ->orWhereIn('id', $dirIds)
                ->get(['id', 'name', 'governorate_id']);
        }

        if (! empty($subAreaValues)) {
            $subAreas = SubArea::whereIn('name', $subAreaNames)
                ->orWhereIn('id', $subAreaIdsFromExcel)
                ->get(['id', 'name', 'directorate_id']);
            $this->subAreaCache = $subAreas;

            // Pre-load existing villages for these SubAreas to avoid per-row queries
            $resolvedSubAreaIds = $subAreas->pluck('id')->toArray();
            if (! empty($resolvedSubAreaIds)) {
                $existing = Village::whereIn('sub_area_id', $resolvedSubAreaIds)
                    ->get(['id', 'sub_area_id', 'name']);

                $this->villageCache = [];
                foreach ($existing as $v) {
                    $this->villageCache[$v->sub_area_id.'_'.$this->normalizeArabic($v->name)] = $v->id;
                }
            }
        }
    }

    private function mapRow($row)
    {
        $data = [];

        // Resolve Governorate
        $govVal = trim((string) ($row[$this->mapping['governorate_id']] ?? ''));
        $normGov = $this->normalizeArabic($govVal);
        if (! $govVal || ! isset($this->governorateCache[$normGov])) {
            return ['error' => "المحافظة غير موجودة: $govVal"];
        }
        $data['governorate_id'] = $this->governorateCache[$normGov];

        // Resolve Directorate
        $dirVal = trim((string) ($row[$this->mapping['directorate_id']] ?? ''));
        $normDir = $this->normalizeArabic($dirVal);
        $directorate = $this->directorateCache->filter(function ($d) use ($normDir, $dirVal, $data) {
            return ($this->normalizeArabic($d->name) == $normDir || $d->id == $dirVal) && $d->governorate_id == $data['governorate_id'];
        })->first();

        if (! $directorate) {
            return ['error' => "المديرية غير موجودة أو لا تتبع المحافظة: $dirVal"];
        }
        $data['directorate_id'] = $directorate->id;

        // Resolve SubArea
        $subAreaVal = trim((string) ($row[$this->mapping['sub_area_id']] ?? ''));
        $normSub = $this->normalizeArabic($subAreaVal);
        $subArea = $this->subAreaCache->filter(function ($s) use ($normSub, $subAreaVal, $data) {
            return ($this->normalizeArabic($s->name) == $normSub || $s->id == $subAreaVal) && $s->directorate_id == $data['directorate_id'];
        })->first();

        if (! $subArea) {
            return ['error' => "العزلة غير موجودة أو لا تتبع المديرية: $subAreaVal"];
        }
        $data['sub_area_id'] = $subArea->id;

        // Village Name
        $data['name'] = trim((string) ($row[$this->mapping['name']] ?? ''));
        if (empty($data['name'])) {
            return ['error' => 'اسم القرية مفقود'];
        }

        // Existence check normalization
        $data['cache_key'] = $data['sub_area_id'].'_'.$this->normalizeArabic($data['name']);

        return $data;
    }

    private function updateProgress($created, $updated, $failed, $errors)
    {
        if ($this->progress) {
            $this->progress->increment('processed_rows', $created + $updated + $failed);
            $this->progress->increment('created_count', $created);
            $this->progress->increment('updated_count', $updated);
            $this->progress->increment('failed_count', $failed);

            if (! empty($errors)) {
                $currentErrors = $this->progress->errors ?? [];
                $newErrors = array_unique(array_merge($currentErrors, $errors));
                $this->progress->update(['errors' => array_slice($newErrors, 0, 100)]);
            }
        }
    }

    public function chunkSize(): int
    {
        return 500;
    }
}
