<?php

namespace Modules\Differences\Services;

use Modules\Differences\Models\DifferenceSearchTarget;

class DynamicRegistryService
{
    /**
     * Stream active system target records in a memory-efficient way using Generators
     */
    public function streamSystemRecords(): \Generator
    {
        $targets = DifferenceSearchTarget::where('is_active', true)->get();
        
        foreach ($targets as $target) {
            if ($target->type === 'static') {
                $modelClass = $target->source_identifier;
                if (!class_exists($modelClass)) {
                    continue;
                }
                
                $query = $modelClass::query();
                // Stream with lazy collections (chunks under the hood)
                foreach ($query->lazy(500) as $record) {
                    $mappings = $target->columns;
                    yield [
                        'id' => $record->{$mappings['id'] ?? ''} ?? null,
                        'name' => $record->{$mappings['name'] ?? ''} ?? null,
                        'phone' => $record->{$mappings['phone'] ?? ''} ?? null,
                        'address' => $record->{$mappings['address'] ?? ''} ?? null,
                        '_source_target_id' => $target->id,
                        '_source_target_name' => $target->name,
                        '_source_table' => $record->getTable(),
                        '_source_page' => $target->name,
                        '_source_page_type' => 'static',
                        '_original_record_id' => $record->getKey(),
                        '_original_record_data' => $record->toArray(),
                        '_record_status' => $record->status ?? $record->record_status ?? 'نشط'
                    ];
                }
            } elseif ($target->type === 'dynamic') {
                $page = \App\Models\Page::where('slug', $target->source_identifier)->first();
                if (!$page) {
                    continue;
                }
                
                $recordsQuery = \App\Models\Record::where('page_id', $page->id)->with('values.field');
                foreach ($recordsQuery->lazy(500) as $record) {
                    $mappedData = [
                        'id' => null,
                        'name' => null,
                        'phone' => null,
                        'address' => null,
                    ];
                    $mappings = $target->columns;
                    
                    foreach ($record->values as $val) {
                        $field = $val->field;
                        if (!$field) continue;
                        foreach ($mappings as $mappedType => $fieldId) {
                            if ((int)$fieldId === (int)$field->id) {
                                $mappedData[$mappedType] = $val->value;
                            }
                        }
                    }
                    
                    $mappedData['_source_target_id'] = $target->id;
                    $mappedData['_source_target_name'] = $target->name;
                    $mappedData['_source_table'] = 'dynamic_records';
                    $mappedData['_source_page'] = $page->name;
                    $mappedData['_source_page_type'] = 'dynamic';
                    $mappedData['_original_record_id'] = $record->id;
                    $mappedData['_original_record_data'] = $record->values->mapWithKeys(function($v) {
                        return [$v->field->label ?? $v->field->name => $v->value];
                    })->toArray();
                    $mappedData['_record_status'] = 'نشط';
                    
                    yield $mappedData;
                }
            } elseif ($target->type === 'custom_table') {
                $dataTable = \App\Models\DataTable::find($target->source_identifier);
                if (!$dataTable) {
                    continue;
                }
                
                $recordsQuery = \App\Models\DataRecord::where('data_table_id', $dataTable->id);
                foreach ($recordsQuery->lazy(500) as $record) {
                    $mappings = $target->columns;
                    $mappedData = [
                        'id' => $record->data[$mappings['id'] ?? ''] ?? null,
                        'name' => $record->data[$mappings['name'] ?? ''] ?? null,
                        'phone' => $record->data[$mappings['phone'] ?? ''] ?? null,
                        'address' => $record->data[$mappings['address'] ?? ''] ?? null,
                    ];
                    
                    $mappedData['_source_target_id'] = $target->id;
                    $mappedData['_source_target_name'] = $target->name;
                    $mappedData['_source_table'] = 'data_records';
                    $mappedData['_source_page'] = $dataTable->name;
                    $mappedData['_source_page_type'] = 'custom_table';
                    $mappedData['_original_record_id'] = $record->id;
                    $mappedData['_original_record_data'] = $record->data;
                    $mappedData['_record_status'] = 'مستورد';
                    
                    yield $mappedData;
                }
            }
        }
    }
}
