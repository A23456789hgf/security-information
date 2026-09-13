<?php

namespace App\Services;

use App\Models\Page;
use App\Models\Record;
use App\Models\RecordValue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class DynamicImportService
{
    /**
     * Create a new import_progress record and return its ID.
     */
    public static function createProgressRecord(string $uuid, Page $page, string $originalFileName): int
    {
        $userId = auth()->id();
        $data = [
            'session_id' => $uuid,
            'type' => 'import',
            'total_rows' => 0,
            'processed_rows' => 0,
            'created_count' => 0,
            'updated_count' => 0,
            'failed_count' => 0,
            'status' => 'pending',
            'errors' => null,
        ];
        $columns = DB::getSchemaBuilder()->getColumnListing('import_progress');
        if (in_array('user_id', $columns)) {
            $data['user_id'] = $userId;
        }
        if (in_array('page_id', $columns)) {
            $data['page_id'] = $page->id;
        }
        if (in_array('file_name', $columns)) {
            $data['file_name'] = $originalFileName;
        }
        return DB::table('import_progress')->insertGetId($data);
    }

    /**
     * Mark import_progress as completed.
     */
    public static function finalizeProgressRecord(int $id, int $processedRows): void
    {
        DB::table('import_progress')->where('id', $id)->update([
            'status' => 'completed',
            'processed_rows' => $processedRows,
            'updated_at' => now(),
        ]);
    }

    /**
     * Generate and download the XLSX template (Data + Instructions sheets).
     */
    public function downloadTemplate(Page $page)
    {
        $fields = $page->fields()->pluck('label', 'name')->toArray();
        $dataHeaders = array_keys($fields);
        $dataSheet = [$dataHeaders];
        $instructions = [
            ['إرشادات الاستيراد:'],
            ['1. يجب أن يكون الملف بصيغة .xlsx أو .xls أو .csv فقط.'],
            ['2. الصف الأول يجب أن يحتوي على أسماء الحقول كما في العمود "الاسم".'],
            ['3. لا تضع بيانات في ورقة "Instructions".'],
        ];
        return Excel::download(new class($dataSheet, $instructions) implements \Maatwebsite\Excel\Concerns\WithMultipleSheets {
            private $dataSheet;
            private $instructions;
            public function __construct($dataSheet, $instructions)
            {
                $this->dataSheet = $dataSheet;
                $this->instructions = $instructions;
            }
            public function sheets(): array
            {
                return [
                    new class($this->dataSheet) implements \Maatwebsite\Excel\Concerns\FromArray, \Maatwebsite\Excel\Concerns\WithTitle {
                        private $data;
                        public function __construct($data) { $this->data = $data; }
                        public function array(): array { return $this->data; }
                        public function title(): string { return 'Data'; }
                    },
                    new class($this->instructions) implements \Maatwebsite\Excel\Concerns\FromArray, \Maatwebsite\Excel\Concerns\WithTitle {
                        private $data;
                        public function __construct($data) { $this->data = $data; }
                        public function array(): array { return $this->data; }
                        public function title(): string { return 'Instructions'; }
                    },
                ];
            }
        }, "template_{$page->slug}.xlsx");
    }

    /**
     * Extract headers (first row) from the Data sheet of the uploaded file.
     */
    public function extractHeaders(string $filePath): array
    {
        $sheets = Excel::toArray([], $filePath);
        $dataSheet = $sheets[0] ?? [];
        return $dataSheet[0] ?? [];
    }

    /**
     * Extract all rows (starting from row 2) from the Data sheet.
     */
    public function extractRows(string $filePath): array
    {
        $sheets = Excel::toArray([], $filePath);
        $dataSheet = $sheets[0] ?? [];
        return array_slice($dataSheet, 1);
    }

    /**
     * Validate rows against mapping and required fields.
     * Returns ['validRows' => [...], 'errors' => [...]]
     */
    public function validateRows(array $rows, array $mapping, Page $page): array
    {
        $requiredFields = $page->fields()->where('required', true)->pluck('id')->toArray();
        $errors = [];
        $validRows = [];
        foreach ($rows as $rowIndex => $row) {
            $rowNumber = $rowIndex + 2; // header is row 1
            foreach ($mapping as $fieldId => $headerIdx) {
                if ($headerIdx === null || $headerIdx === '') {
                    continue;
                }
                $value = $row[$headerIdx] ?? null;
                if (in_array($fieldId, $requiredFields) && (is_null($value) || $value === '')) {
                    $errors[] = [
                        'row' => $rowNumber,
                        'column' => $headerIdx,
                        'field_id' => $fieldId,
                        'value' => $value,
                        'message' => 'القيمة مطلوبة لهذا الحقل.',
                    ];
                }
            }
            if (empty($errors)) {
                $validRows[] = $row;
            }
        }
        return ['validRows' => $validRows, 'errors' => $errors];
    }

    /**
     * Return a preview of the first 5 valid rows.
     */
    public function previewRows(array $validRows): array
    {
        return array_slice($validRows, 0, 5);
    }

    /**
     * Store validated rows into the database within a transaction.
     */
    public function storeRows(array $validRows, array $mapping, Page $page): void
    {
        DB::transaction(function () use ($validRows, $mapping, $page) {
            foreach ($validRows as $row) {
                $record = Record::create([
                    'page_id' => $page->id,
                    'created_by' => auth()->id(),
                ]);
                foreach ($mapping as $fieldId => $headerIdx) {
                    if ($headerIdx === null || $headerIdx === '') {
                        continue;
                    }
                    $value = $row[$headerIdx] ?? null;
                    $record->values()->create([
                        'field_id' => $fieldId,
                        'value' => $value,
                    ]);
                }
            }
        });
    }
}
?>
