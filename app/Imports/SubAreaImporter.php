<?php

namespace App\Imports;

use App\Models\Directorate;
use App\Models\Governorate;
use App\Models\ImportProgress;
use App\Models\SubArea;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;

class SubAreaImporter implements ToCollection, WithChunkReading, WithStartRow
{
    private $sessionId;

    private $mapping;

    private $mode;

    private $progress;

    // Caches
    private $governorateCache = [];

    private $directorateCache = [];

    private $subAreaCacheMap = [];

    public function __construct($sessionId, $mapping, $mode)
    {
        $this->sessionId = $sessionId;
        $this->mapping = $mapping;
        $this->mode = $mode;
        $this->progress = ImportProgress::where('session_id', $sessionId)->first();
    }

    public function startRow(): int
    {
        return 2;
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
            $cacheKey = $data['directorate_id'].'_'.strtolower($data['name']);
            $exists = isset($this->subAreaCacheMap[$cacheKey]);

            if ($this->mode === 'append' && $exists) {
                $skipped++;

                continue;
            }

            if ($exists) {
                $updated++;
            } else {
                $created++;
                // Mark as seen in this chunk to prevent internal duplicates
                $this->subAreaCacheMap[$cacheKey] = true;
            }

            $toUpsert[] = array_merge($data, [
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (! empty($toUpsert)) {
            DB::transaction(function () use ($toUpsert) {
                // Bulk Upsert: 1 query for 500 rows!
                // We unique on [directorate_id, name] as per DB constraint
                SubArea::upsert($toUpsert, ['directorate_id', 'name'], ['governorate_id', 'updated_at']);
            });
        }

        $this->updateProgress($created, $updated, $failed, $errors);
    }

    private function preloadCaches(Collection $rows)
    {
        $govValues = [];
        $dirValues = [];
        $subAreaNames = [];

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
            if (isset($this->mapping['name'])) {
                $val = $rowArray[$this->mapping['name']] ?? null;
                if ($val) {
                    $subAreaNames[] = trim((string) $val);
                }
            }
        }

        $govValues = array_unique(array_filter($govValues));
        $dirValues = array_unique(array_filter($dirValues));
        $subAreaNames = array_unique(array_filter($subAreaNames));

        // Separate names and IDs
        $govNamesFromExcel = array_filter($govValues, fn ($v) => ! is_numeric($v));
        $govIdsFromExcel = array_filter($govValues, fn ($v) => is_numeric($v));

        $dirNamesFromExcel = array_filter($dirValues, fn ($v) => ! is_numeric($v));
        $dirIdsFromExcel = array_filter($dirValues, fn ($v) => is_numeric($v));

        if (! empty($govValues)) {
            $this->governorateCache = Governorate::whereIn('name', $govNamesFromExcel)
                ->orWhereIn('id', $govIdsFromExcel)
                ->pluck('id', 'name')->toArray();
            // Also store IDs in cache for fast lookup
            foreach ($this->governorateCache as $name => $id) {
                $this->governorateCache[$id] = $id;
            }
        }
        if (! empty($dirValues)) {
            $this->directorateCache = Directorate::whereIn('name', $dirNamesFromExcel)
                ->orWhereIn('id', $dirIdsFromExcel)
                ->get(['id', 'name', 'governorate_id']);
        }

        // Pre-load existing SubAreas for these directorates
        $dirIds = $this->directorateCache->pluck('id')->toArray();
        if (! empty($dirIds)) {
            $existing = SubArea::whereIn('directorate_id', $dirIds)
                ->get(['id', 'directorate_id', 'name']);

            $this->subAreaCacheMap = [];
            foreach ($existing as $s) {
                $this->subAreaCacheMap[$s->directorate_id.'_'.strtolower(trim($s->name))] = $s->id;
            }
        }
    }

    private function mapRow($row)
    {
        $data = [];

        // Resolve Governorate
        $govVal = trim((string) ($row[$this->mapping['governorate_id']] ?? ''));
        if (! $govVal || ! isset($this->governorateCache[$govVal])) {
            return ['error' => "المحافظة غير موجودة: $govVal"];
        }
        $data['governorate_id'] = $this->governorateCache[$govVal];

        // Resolve Directorate
        $dirVal = trim((string) ($row[$this->mapping['directorate_id']] ?? ''));
        $directorate = $this->directorateCache->filter(function ($d) use ($dirVal, $data) {
            return ($d->name == $dirVal || $d->id == $dirVal) && $d->governorate_id == $data['governorate_id'];
        })->first();

        if (! $directorate) {
            return ['error' => "المديرية غير موجودة أو لا تتبع المحافظة: $dirVal"];
        }
        $data['directorate_id'] = $directorate->id;

        // SubArea Name
        $data['name'] = trim((string) ($row[$this->mapping['name']] ?? ''));
        if (empty($data['name'])) {
            return ['error' => 'اسم العزلة مفقود'];
        }

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
