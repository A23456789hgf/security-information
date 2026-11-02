<?php

namespace App\Services;

use App\Models\Arrest;
use App\Models\Course;
use App\Models\DataRecord;
use App\Models\DataTable;
use App\Models\DifferentiationLog;
use App\Models\Field;
use App\Models\Page;
use App\Models\PersonalData;
use App\Models\Record;
use App\Models\Relative;
use App\Models\SecurityFile;
use App\Models\SecurityReport;
use App\Models\Suspect;
use App\Models\Wife;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;

class DifferentiationService
{
    /**
     * Parse uploaded file and extract headers and rows sample.
     */
    public function parseUploadedFile($file)
    {
        $spreadsheet = IOFactory::load($file->getPathname());
        $worksheet = $spreadsheet->getActiveSheet();
        $rawRows = $worksheet->toArray(null, true, true, false);

        if (empty($rawRows)) {
            throw new \Exception('الملف المرفوع فارغ ولا يحتوي على بيانات.');
        }

        // Clean empty leading rows
        $headers = [];
        while (!empty($rawRows)) {
            $potentialHeaders = array_shift($rawRows);
            $cleanHeaders = array_filter(array_map('trim', $potentialHeaders), function ($val) {
                return !is_null($val) && $val !== '';
            });

            if (!empty($cleanHeaders)) {
                $headers = $potentialHeaders;
                break;
            }
        }

        if (empty($headers)) {
            throw new \Exception('تعذر استخراج أسماء الأعمدة من الملف.');
        }

        // Sanitize headers
        $sanitizedHeaders = [];
        foreach ($headers as $idx => $h) {
            $val = trim((string)$h);
            $sanitizedHeaders[] = $val !== '' ? $val : "عمود " . ($idx + 1);
        }

        $rows = [];
        foreach ($rawRows as $r) {
            // Check if row has any non-null data
            $hasData = false;
            $rowObj = [];
            foreach ($sanitizedHeaders as $idx => $colName) {
                $cellVal = isset($r[$idx]) ? trim((string)$r[$idx]) : '';
                if ($cellVal !== '') {
                    $hasData = true;
                }
                $rowObj[$colName] = $cellVal;
            }
            if ($hasData) {
                $rows[] = $rowObj;
            }
        }

        return [
            'headers' => $sanitizedHeaders,
            'rows' => $rows,
            'count' => count($rows),
            'sample' => array_slice($rows, 0, 10),
        ];
    }

    /**
     * Get list of all available system pages and models for comparison.
     */
    public function getAvailableSystemSources()
    {
        $sources = [
            [
                'key' => 'personal_data',
                'name' => 'البيانات الشخصية (Personal Data)',
                'type' => 'static_model',
            ],
            [
                'key' => 'suspects',
                'name' => 'المشتبه بهم (Suspects)',
                'type' => 'static_model',
            ],
            [
                'key' => 'arrests',
                'name' => 'سجلات الضبط والتوقيف (Arrests)',
                'type' => 'static_model',
            ],
            [
                'key' => 'security_reports',
                'name' => 'البلاغات الأمنية (Security Reports)',
                'type' => 'static_model',
            ],
            [
                'key' => 'courses',
                'name' => 'الدورات التدريبية (Courses)',
                'type' => 'static_model',
            ],
            [
                'key' => 'security_files',
                'name' => 'الملفات الأمنية (Security Files)',
                'type' => 'static_model',
            ],
            [
                'key' => 'wives',
                'name' => 'بيانات الزوجات (Wives)',
                'type' => 'static_model',
            ],
            [
                'key' => 'relatives',
                'name' => 'الأقارب (Relatives)',
                'type' => 'static_model',
            ],
        ];

        // Append dynamic pages dynamically discovered from database
        try {
            $dynamicPages = Page::where('is_active', true)->get();
            foreach ($dynamicPages as $page) {
                $sources[] = [
                    'key' => 'dynamic_page_' . $page->id,
                    'name' => 'صفحة ديناميكية: ' . $page->name,
                    'type' => 'dynamic_page',
                    'page_id' => $page->id,
                ];
            }
        } catch (\Exception $e) {
            Log::warning('Error fetching dynamic pages for differentiation: ' . $e->getMessage());
        }

        return $sources;
    }

    /**
     * Get available fields and labels for a specific system source.
     */
    public function getSystemSourceFields($sourceKey)
    {
        if (str_starts_with($sourceKey, 'dynamic_page_')) {
            $pageId = (int) str_replace('dynamic_page_', '', $sourceKey);
            $page = Page::with('fields')->find($pageId);
            if (!$page) {
                return [];
            }
            $fields = [];
            foreach ($page->fields as $field) {
                $fields[] = [
                    'key' => $field->name,
                    'label' => $field->label ?: $field->name,
                ];
            }
            return $fields;
        }

        switch ($sourceKey) {
            case 'personal_data':
                return [
                    ['key' => 'name', 'label' => 'الاسم الكامل'],
                    ['key' => 'jihadi_name', 'label' => 'الاسم الجهادي'],
                    ['key' => 'mother_name', 'label' => 'اسم الأم'],
                    ['key' => 'military_number', 'label' => 'الرقم العسكري'],
                    ['key' => 'national_id', 'label' => 'الرقم الوطني / الهوية'],
                    ['key' => 'phone_1', 'label' => 'رقم الهاتف الأول'],
                    ['key' => 'phone_2', 'label' => 'رقم الهاتف الثاني'],
                    ['key' => 'birth_date', 'label' => 'تاريخ الميلاد'],
                    ['key' => 'birth_place', 'label' => 'مكان الميلاد'],
                ];
            case 'suspects':
                return [
                    ['key' => 'suspect_name', 'label' => 'اسم المشتبه به'],
                    ['key' => 'status', 'label' => 'مستوى الخطورة / الحالة'],
                    ['key' => 'reason', 'label' => 'سبب التوصيف'],
                    ['key' => 'notes', 'label' => 'ملاحظات'],
                ];
            case 'arrests':
                return [
                    ['key' => 'arrest_date', 'label' => 'تاريخ الضبط'],
                    ['key' => 'location', 'label' => 'مكان الضبط'],
                    ['key' => 'notes', 'label' => 'ملاحظات التوقيف'],
                ];
            case 'security_reports':
                return [
                    ['key' => 'report_number', 'label' => 'رقم البلاغ'],
                    ['key' => 'report_date', 'label' => 'تاريخ البلاغ'],
                    ['key' => 'title', 'label' => 'عنوان البلاغ'],
                    ['key' => 'details', 'label' => 'تفاصيل البلاغ'],
                ];
            case 'courses':
                return [
                    ['key' => 'start_date', 'label' => 'تاريخ البداية'],
                    ['key' => 'end_date', 'label' => 'تاريخ النهاية'],
                    ['key' => 'grade', 'label' => 'التقدير / النتيجة'],
                    ['key' => 'location', 'label' => 'مكان الدورة'],
                ];
            case 'security_files':
                return [
                    ['key' => 'file_number', 'label' => 'رقم الملف الأمنية'],
                    ['key' => 'file_type', 'label' => 'نوع الملف'],
                    ['key' => 'description', 'label' => 'وصف الملف'],
                ];
            case 'wives':
                return [
                    ['key' => 'wife_name', 'label' => 'اسم الزوجة'],
                    ['key' => 'national_id', 'label' => 'رقم الهوية'],
                    ['key' => 'notes', 'label' => 'ملاحظات'],
                ];
            case 'relatives':
                return [
                    ['key' => 'relative_name', 'label' => 'اسم القريب'],
                    ['key' => 'phone', 'label' => 'الهاتف'],
                    ['key' => 'national_id', 'label' => 'رقم الهوية'],
                ];
            default:
                return [];
        }
    }

    /**
     * Fetch records array from a system source (Eloquent Model or Dynamic Page).
     */
    public function fetchSystemSourceRows($sourceKey)
    {
        $rows = [];

        if (str_starts_with($sourceKey, 'dynamic_page_')) {
            $pageId = (int) str_replace('dynamic_page_', '', $sourceKey);
            $page = Page::with(['fields', 'records.values.field'])->find($pageId);
            if (!$page) {
                return [];
            }
            foreach ($page->records as $rec) {
                $row = [];
                foreach ($rec->values as $val) {
                    if ($val->field) {
                        $row[$val->field->name] = (string)$val->value;
                    }
                }
                if (!empty($row)) {
                    $rows[] = $row;
                }
            }
            return $rows;
        }

        switch ($sourceKey) {
            case 'personal_data':
                $items = UserScopeService::applyScope(PersonalData::query())->get();
                foreach ($items as $item) {
                    $rows[] = [
                        'name' => (string)$item->name,
                        'jihadi_name' => (string)$item->jihadi_name,
                        'mother_name' => (string)$item->mother_name,
                        'military_number' => (string)$item->military_number,
                        'national_id' => (string)$item->national_id,
                        'phone_1' => (string)$item->phone_1,
                        'phone_2' => (string)$item->phone_2,
                        'birth_date' => $item->birth_date ? (string)$item->birth_date : '',
                        'birth_place' => (string)$item->birth_place,
                    ];
                }
                break;
            case 'suspects':
                $items = UserScopeService::applyScope(Suspect::query())->get();
                foreach ($items as $item) {
                    $rows[] = [
                        'suspect_name' => (string)$item->suspect_name,
                        'status' => (string)$item->status,
                        'reason' => (string)$item->reason,
                        'notes' => (string)$item->notes,
                    ];
                }
                break;
            case 'arrests':
                $items = UserScopeService::applyScope(Arrest::query())->get();
                foreach ($items as $item) {
                    $rows[] = [
                        'arrest_date' => $item->arrest_date ? (string)$item->arrest_date : '',
                        'location' => (string)$item->location,
                        'notes' => (string)$item->notes,
                    ];
                }
                break;
            case 'security_reports':
                $items = UserScopeService::applyScope(SecurityReport::query())->get();
                foreach ($items as $item) {
                    $rows[] = [
                        'report_number' => (string)$item->report_number,
                        'report_date' => $item->report_date ? (string)$item->report_date : '',
                        'title' => (string)$item->title,
                        'details' => (string)$item->details,
                    ];
                }
                break;
            case 'courses':
                $items = UserScopeService::applyScope(Course::query())->get();
                foreach ($items as $item) {
                    $rows[] = [
                        'start_date' => $item->start_date ? (string)$item->start_date : '',
                        'end_date' => $item->end_date ? (string)$item->end_date : '',
                        'grade' => (string)$item->grade,
                        'location' => (string)$item->location,
                    ];
                }
                break;
            case 'security_files':
                $items = UserScopeService::applyScope(SecurityFile::query())->get();
                foreach ($items as $item) {
                    $rows[] = [
                        'file_number' => (string)$item->file_number,
                        'file_type' => (string)$item->file_type,
                        'description' => (string)$item->description,
                    ];
                }
                break;
            case 'wives':
                $items = UserScopeService::applyScope(Wife::query())->get();
                foreach ($items as $item) {
                    $rows[] = [
                        'wife_name' => (string)$item->wife_name,
                        'national_id' => (string)$item->national_id,
                        'notes' => (string)$item->notes,
                    ];
                }
                break;
            case 'relatives':
                $items = UserScopeService::applyScope(Relative::query())->get();
                foreach ($items as $item) {
                    $rows[] = [
                        'relative_name' => (string)$item->relative_name,
                        'phone' => (string)$item->phone,
                        'national_id' => (string)$item->national_id,
                    ];
                }
                break;
        }

        return $rows;
    }

    /**
     * Save Main Dataset to database.
     */
    public function saveMainDataset($name, $source, $description, $headers, $rows, $mapping = [], $userId = null)
    {
        // Find existing or create new main table
        $table = DataTable::where('type', 'primary')->first();
        if (!$table) {
            $table = DataTable::create([
                'user_id' => $userId,
                'name' => $name ?: 'البيانات الرئيسية',
                'type' => 'primary',
                'source' => $source ?: 'استيراد',
                'description' => $description ?: 'مجموعة البيانات الرئيسية المسجلة بالسيستم',
                'column_headers' => $headers,
                'field_mappings' => $mapping,
                'records_count' => count($rows),
            ]);
        } else {
            $table->update([
                'user_id' => $userId ?: $table->user_id,
                'name' => $name ?: $table->name,
                'source' => $source ?: $table->source,
                'description' => $description ?: $table->description,
                'column_headers' => $headers,
                'field_mappings' => $mapping,
                'records_count' => count($rows),
            ]);
            $table->records()->delete();
        }

        foreach ($rows as $index => $row) {
            // Apply column mapping if provided
            $mappedRow = [];
            foreach ($headers as $col) {
                $targetField = $mapping[$col] ?? $col;
                if ($targetField !== '<ignore>') {
                    $mappedRow[$targetField] = $row[$col] ?? '';
                }
            }

            $table->records()->create([
                'data' => $mappedRow,
                'sequence' => $index + 1,
            ]);
        }

        // Log operation
        DifferentiationLog::create([
            'user_id' => $userId,
            'action' => 'import_main',
            'dataset_name' => $table->name,
            'details' => [
                'records_count' => count($rows),
                'columns' => $headers,
                'mapping' => $mapping,
            ],
        ]);

        return $table;
    }

    /**
     * Save a new named Secondary Dataset to database.
     */
    public function saveSecondaryDataset($name, $source, $description, $headers, $rows, $mapping = [], $userId = null)
    {
        if (empty($name)) {
            throw new \Exception('يجب إدخال اسم مميز لمجموعة البيانات الثانوية.');
        }

        $table = DataTable::create([
            'user_id' => $userId,
            'name' => $name,
            'type' => 'secondary',
            'source' => $source ?: 'استيراد ثانوية',
            'description' => $description ?: '',
            'column_headers' => $headers,
            'field_mappings' => $mapping,
            'records_count' => count($rows),
        ]);

        foreach ($rows as $index => $row) {
            $mappedRow = [];
            foreach ($headers as $col) {
                $targetField = $mapping[$col] ?? $col;
                if ($targetField !== '<ignore>') {
                    $mappedRow[$targetField] = $row[$col] ?? '';
                }
            }

            $table->records()->create([
                'data' => $mappedRow,
                'sequence' => $index + 1,
            ]);
        }

        // Log operation
        DifferentiationLog::create([
            'user_id' => $userId,
            'action' => 'import_secondary',
            'dataset_name' => $name,
            'details' => [
                'table_id' => $table->id,
                'source' => $source,
                'records_count' => count($rows),
                'columns' => $headers,
                'mapping' => $mapping,
            ],
        ]);

        return $table;
    }

    /**
     * Perform Matching and Comparison between Primary and Secondary data rows.
     * Evaluates 5 Categories:
     * 1. matched
     * 2. different
     * 3. in_sec_only
     * 4. in_pri_only
     * 5. incomplete
     */
    public function compareRows($primaryRows, $secondaryRows, $keyFields = [], $fieldMapping = [])
    {
        if (empty($keyFields)) {
            throw new \Exception('يجب اختيار حقل واحد على الأقل كمفتاح للمطابقة.');
        }

        $matched = [];
        $different = [];
        $inSecOnly = [];
        $inPriOnly = [];
        $incomplete = [];

        // Build index of Primary Rows by key combinations
        $primaryIndex = [];
        $primaryMatchedFlags = [];

        foreach ($primaryRows as $priIdx => $priRow) {
            $primaryMatchedFlags[$priIdx] = false;
            $keyStr = $this->buildCompositeKey($priRow, $keyFields, $fieldMapping, 'primary');
            if ($keyStr !== '') {
                $primaryIndex[$keyStr][] = [
                    'index' => $priIdx,
                    'row' => $priRow,
                ];
            }
        }

        // Compare Secondary Rows against Primary Index
        foreach ($secondaryRows as $secIdx => $secRow) {
            // Check if secondary row has missing required key values
            if ($this->isRecordIncomplete($secRow, $keyFields, $fieldMapping, 'secondary')) {
                $incomplete[] = [
                    'secondary_index' => $secIdx + 1,
                    'secondary_row' => $secRow,
                    'reason' => 'مفقود قيم مفاتيح المطابقة الأحادية أو المركبة',
                ];
                continue;
            }

            $secKeyStr = $this->buildCompositeKey($secRow, $keyFields, $fieldMapping, 'secondary');

            if (isset($primaryIndex[$secKeyStr]) && !empty($primaryIndex[$secKeyStr])) {
                // Match found!
                $priItem = $primaryIndex[$secKeyStr][0]; // take first matching primary record
                $priIdx = $priItem['index'];
                $priRow = $priItem['row'];
                $primaryMatchedFlags[$priIdx] = true;

                // Evaluate non-key mapped field differences
                $diffs = $this->calculateFieldDifferences($priRow, $secRow, $fieldMapping, $keyFields);

                if (empty($diffs)) {
                    $matched[] = [
                        'primary_index' => $priIdx + 1,
                        'secondary_index' => $secIdx + 1,
                        'primary_row' => $priRow,
                        'secondary_row' => $secRow,
                    ];
                } else {
                    $different[] = [
                        'primary_index' => $priIdx + 1,
                        'secondary_index' => $secIdx + 1,
                        'primary_row' => $priRow,
                        'secondary_row' => $secRow,
                        'differences' => $diffs,
                    ];
                }
            } else {
                // Present in Secondary, not in Main
                $inSecOnly[] = [
                    'secondary_index' => $secIdx + 1,
                    'secondary_row' => $secRow,
                ];
            }
        }

        // Identify Primary Rows not matched
        foreach ($primaryRows as $priIdx => $priRow) {
            if (empty($primaryMatchedFlags[$priIdx])) {
                $inPriOnly[] = [
                    'primary_index' => $priIdx + 1,
                    'primary_row' => $priRow,
                ];
            }
        }

        return [
            'summary' => [
                'matched_count' => count($matched),
                'different_count' => count($different),
                'in_sec_only_count' => count($inSecOnly),
                'in_pri_only_count' => count($inPriOnly),
                'incomplete_count' => count($incomplete),
                'total_secondary' => count($secondaryRows),
                'total_primary' => count($primaryRows),
            ],
            'results' => [
                'matched' => $matched,
                'different' => $different,
                'in_sec_only' => $inSecOnly,
                'in_pri_only' => $inPriOnly,
                'incomplete' => $incomplete,
            ],
        ];
    }

    /**
     * Build composite key string for a record based on key fields and mapping.
     */
    private function buildCompositeKey($row, $keyFields, $fieldMapping, $role = 'secondary')
    {
        $parts = [];
        foreach ($keyFields as $kf) {
            $colName = $kf;
            if ($role === 'secondary' && isset($fieldMapping[$kf])) {
                $colName = $fieldMapping[$kf] !== '<ignore>' ? $fieldMapping[$kf] : $kf;
            }
            $val = isset($row[$colName]) ? trim((string)$row[$colName]) : (isset($row[$kf]) ? trim((string)$row[$kf]) : '');
            if ($val === '') {
                return ''; // incomplete key
            }
            $parts[] = mb_strtolower($val);
        }
        return implode('||', $parts);
    }

    /**
     * Check if key values are missing in a record.
     */
    private function isRecordIncomplete($row, $keyFields, $fieldMapping, $role = 'secondary')
    {
        foreach ($keyFields as $kf) {
            $colName = $kf;
            if ($role === 'secondary' && isset($fieldMapping[$kf])) {
                $colName = $fieldMapping[$kf] !== '<ignore>' ? $fieldMapping[$kf] : $kf;
            }
            $val = isset($row[$colName]) ? trim((string)$row[$colName]) : (isset($row[$kf]) ? trim((string)$row[$kf]) : '');
            if ($val === '') {
                return true;
            }
        }
        return false;
    }

    /**
     * Calculate field-by-field differences between primary and secondary rows.
     */
    private function calculateFieldDifferences($priRow, $secRow, $fieldMapping, $keyFields)
    {
        $diffs = [];
        foreach ($secRow as $secCol => $secVal) {
            $targetCol = $fieldMapping[$secCol] ?? $secCol;
            if ($targetCol === '<ignore>' || in_array($secCol, $keyFields) || in_array($targetCol, $keyFields)) {
                continue;
            }

            $priVal = isset($priRow[$targetCol]) ? trim((string)$priRow[$targetCol]) : (isset($priRow[$secCol]) ? trim((string)$priRow[$secCol]) : '');
            $secValClean = trim((string)$secVal);

            if ($priVal !== '' && $secValClean !== '' && mb_strtolower($priVal) !== mb_strtolower($secValClean)) {
                $diffs[] = [
                    'field' => $targetCol,
                    'secondary_col' => $secCol,
                    'primary_val' => $priVal,
                    'secondary_val' => $secValClean,
                ];
            }
        }
        return $diffs;
    }
}

