<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class DataImportController extends Controller
{
    /**
     * Get or create current data table
     */
    private function getCurrentTable($origin = null)
    {
        $type = $origin ?? request('origin', 'default');
        $tableId = session('current_data_table_id');
        
        if ($tableId) {
            $table = \App\Models\DataTable::find($tableId);
            if ($table) return $table;
        }

        // Try to find an existing table for this type
        $table = \App\Models\DataTable::where('type', $type)->latest()->first();
        
        if (!$table) {
            $table = \App\Models\DataTable::create([
                'name' => 'Import ' . now()->format('Y-m-d H:i'),
                'type' => $type,
                'column_headers' => []
            ]);
        }
        
        session(['current_data_table_id' => $table->id]);
        return $table;
    }

    /**
     * Display the main data import page
     */
    public function index()
    {
        $table = $this->getCurrentTable();
        $columns = $table->column_headers ?? [];
        $data = $table->records()->orderBy('sequence')->get()->pluck('data')->toArray();
        
        return view('data.import.index', compact('columns', 'data'));
    }

    /**
     * Parse Excel file into arrays using Laravel Excel
     */
    private function parseExcel($file)
    {
        try {
            $data = Excel::toArray(new class {}, $file);
            return $data[0] ?? []; // Return first sheet
        } catch (\Exception $e) {
            \Log::error('Laravel-Excel load error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Handle Excel file upload and parse
     */
    public function import(Request $request)
    {
        \Log::debug('--- Import Started ---');
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv'
        ]);

        $file = $request->file('excel_file');
        if (!$file) {
            \Log::error('No file found in request');
            return response()->json(['success' => false, 'message' => 'لم يتم تحميل أي ملف']);
        }

        \Log::debug('Importing file: ' . $file->getClientOriginalName());

        $rows = $this->parseExcel($file);
        if ($rows === null) {
            \Log::error('parseExcel returned null');
            return response()->json(['success' => false, 'message' => 'خطأ في قراءة ملف الإكسل']);
        }

        \Log::debug('Rows count from parser: ' . count($rows));

        if (empty($rows)) {
            \Log::warning('Rows array is empty after parsing');
            return response()->json(['success' => false, 'message' => 'الملف فارغ']);
        }

        // Find the first non-empty row to use as headers
        $headers = [];
        $headerKeys = [];
        $columns = [];
        
        while (!empty($rows)) {
            $potentialHeaders = array_shift($rows);
            $validColumns = array_filter($potentialHeaders, function($h) { 
                return !is_null($h) && trim((string)$h) !== ''; 
            });
            
            if (!empty($validColumns)) {
                $headers = $potentialHeaders;
                $headerKeys = array_keys($validColumns);
                $columns = array_values($validColumns);
                \Log::debug('Headers detected: ' . implode(', ', $columns));
                break;
            }
        }

        if (empty($columns)) {
            \Log::error('No columns detected in file');
            return response()->json(['success' => false, 'message' => 'تعذر العثور على رؤوس أعمدة في الملف']);
        }

        // Create a fresh table for this import
        try {
            $table = \App\Models\DataTable::create([
                'name' => $file->getClientOriginalName(),
                'type' => $request->input('origin', 'default'),
                'column_headers' => $columns
            ]);
            \Log::debug('Created DataTable ID: ' . $table->id);
        } catch (\Exception $e) {
            \Log::error('Failed to create DataTable: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'خطأ في إنشاء الجدول في قاعدة البيانات']);
        }

        session(['current_data_table_id' => $table->id]);

        $count = 0;
        \Log::debug('Starting record creation loop. Rows remaining: ' . count($rows));
        
        foreach ($rows as $index => $row) {
            $alignedRow = [];
            $hasData = false;
            foreach ($headerKeys as $key) {
                $value = $row[$key] ?? '';
                if (is_object($value)) {
                    $value = method_exists($value, '__toString') ? (string)$value : json_encode($value);
                }
                $alignedRow[] = $value;
                if (!is_null($value) && trim((string)$value) !== '') $hasData = true;
            }
            
            if ($hasData) {
                try {
                    $table->records()->create([
                        'data' => $alignedRow,
                        'sequence' => $index
                    ]);
                    $count++;
                } catch (\Exception $e) {
                    \Log::error('Failed to create record at index ' . $index . ': ' . $e->getMessage());
                }
            }
        }

        \Log::debug('Successfully created ' . $count . ' records');
        \Log::debug('--- Import Finished ---');

        return response()->json([
            'success' => true,
            'columns' => $columns,
            'preview_data' => array_slice($rows, 0, 5),
            'total_rows' => $count
        ]);
    }

    /**
     * Store data (usually called after some bulk JS operations)
     */
    public function store(Request $request)
    {
        $columns = $request->input('columns', []);
        $data = $request->input('data', []);
        
        $table = $this->getCurrentTable();
        $table->update(['column_headers' => $columns]);
        
        // This is a full refresh of data
        $table->records()->delete();
        foreach ($data as $index => $row) {
            $table->records()->create([
                'data' => $row,
                'sequence' => $index
            ]);
        }

        return response()->json(['success' => true, 'message' => 'تم حفظ البيانات بنجاح في قاعدة البيانات']);
    }

    /**
     * Create new empty table with custom columns
     */
    public function createTable(Request $request)
    {
        $columns = $request->input('columns', []);
        
        $table = \App\Models\DataTable::create([
            'name' => 'New Table ' . now()->format('H:i'),
            'type' => $request->input('origin', 'default'),
            'column_headers' => $columns
        ]);
        
        session(['current_data_table_id' => $table->id]);

        return response()->json(['success' => true, 'message' => 'تم إنشاء الجدول بنجاح']);
    }

    /**
     * Replace entire table from Excel file
     */
    public function replaceTable(Request $request)
    {
        return $this->import($request);
    }

    /**
     * Merge data from another Excel file
     */
    public function mergeData(Request $request)
    {
        \Log::debug('--- Merge Started ---');
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv'
        ]);

        $file = $request->file('excel_file');
        if (!$file) {
            \Log::error('No file found in merge request');
            return response()->json(['success' => false, 'message' => 'لم يتم تحميل أي ملف']);
        }

        $rows = $this->parseExcel($file);
        if (!$rows) {
            \Log::error('parseExcel returned null in merge');
            return response()->json(['success' => false, 'message' => 'خطأ في قراءة ملف الإكسل']);
        }

        \Log::debug('Rows count in merge: ' . count($rows));

        $table = $this->getCurrentTable();
        \Log::debug('Merging into Table ID: ' . $table->id);
        
        $existingColumns = $table->column_headers ?? [];
        \Log::debug('Existing columns: ' . json_encode($existingColumns));
        
        // Find headers in the new file to align columns
        $newHeaderKeys = [];
        $hasMatchedAnyColumn = false;
        $newFileHeaders = [];

        while (!empty($rows)) {
            $potentialHeaders = array_shift($rows);
            $validColumns = array_filter($potentialHeaders, function($h) { 
                return !is_null($h) && trim((string)$h) !== ''; 
            });
            
            if (!empty($validColumns)) {
                $newFileHeaders = $potentialHeaders;
                \Log::debug('Merge file headers detected: ' . implode(', ', $potentialHeaders));
                
                // If the current table has no headers, adopt these!
                if (empty($existingColumns)) {
                    $existingColumns = array_values($validColumns);
                    $table->update(['column_headers' => $existingColumns]);
                    $headerKeys = array_keys($validColumns);
                    foreach ($headerKeys as $key) {
                        $newHeaderKeys[] = $key;
                    }
                    $hasMatchedAnyColumn = true;
                    \Log::debug('Table was empty, adopted headers: ' . implode(', ', $existingColumns));
                } else {
                    // Map new file columns to existing table columns by name
                    foreach ($existingColumns as $existingCol) {
                        $found = false;
                        $normalizedExisting = trim(mb_strtolower((string)$existingCol));
                        
                        foreach ($potentialHeaders as $index => $newCol) {
                            if (trim(mb_strtolower((string)$newCol)) === $normalizedExisting) {
                                $newHeaderKeys[] = $index;
                                $found = true;
                                $hasMatchedAnyColumn = true;
                                break;
                            }
                        }
                        if (!$found) {
                            $newHeaderKeys[] = null; // Mark as missing in the new file
                        }
                    }
                }
                break;
            }
        }

        \Log::debug('Merge matched any column: ' . ($hasMatchedAnyColumn ? 'Yes' : 'No'));
        \Log::debug('Merge mapping keys: ' . json_encode($newHeaderKeys));

        // If no headers matched, fallback to index-based merge
        if (!$hasMatchedAnyColumn) {
            $newHeaderKeys = array_keys($existingColumns);
            \Log::debug('Fallback to index-based merge keys: ' . json_encode($newHeaderKeys));
        }

        $lastSequence = $table->records()->max('sequence') ?? -1;
        $count = 0;

        \Log::debug('Starting merge record creation loop. Rows remaining: ' . count($rows));

        foreach ($rows as $index => $row) {
            $alignedRow = [];
            $hasData = false;
            
            foreach ($newHeaderKeys as $key) {
                $value = '';
                if ($key !== null && isset($row[$key])) {
                    $value = $row[$key];
                    if (is_object($value)) {
                        $value = method_exists($value, '__toString') ? (string)$value : json_encode($value);
                    }
                    if (!is_null($value) && trim((string)$value) !== '') $hasData = true;
                }
                $alignedRow[] = $value;
            }
            
            if ($hasData) {
                try {
                    $table->records()->create([
                        'data' => $alignedRow,
                        'sequence' => $lastSequence + $count + 1
                    ]);
                    $count++;
                } catch (\Exception $e) {
                    \Log::error('Merge failed at row ' . $index . ': ' . $e->getMessage());
                }
            }
        }
        
        \Log::debug("Merge successfully added $count records.");
        \Log::debug('--- Merge Finished ---');

        return response()->json([
            'success' => true,
            'message' => "تم دمج $count سجل بنجاح"
        ]);
    }

    /**
     * Insert a new row
     */
    public function insertRow(Request $request)
    {
        $table = $this->getCurrentTable();
        $position = $request->input('position');
        $columnsCount = count($table->column_headers);
        
        $newRowData = array_fill(0, $columnsCount, '');
        
        if ($position === null) {
            $sequence = ($table->records()->max('sequence') ?? -1) + 1;
            $table->records()->create(['data' => $newRowData, 'sequence' => $sequence]);
        } else {
            // Shift existing records
            $table->records()->where('sequence', '>=', $position)->increment('sequence');
            $table->records()->create(['data' => $newRowData, 'sequence' => $position]);
        }
        
        $allData = $table->records()->orderBy('sequence')->get()->pluck('data')->toArray();

        return response()->json(['success' => true, 'data' => $allData]);
    }

    /**
     * Delete selected rows
     */
    public function deleteRows(Request $request)
    {
        $indices = $request->input('indices', []); // Assuming sequence/index
        $table = $this->getCurrentTable();
        
        foreach ($indices as $index) {
            $table->records()->where('sequence', $index)->delete();
        }
        
        // Re-sequence remaining records
        $records = $table->records()->orderBy('sequence')->get();
        foreach ($records as $i => $record) {
            $record->update(['sequence' => $i]);
        }
        
        $allData = $records->pluck('data')->toArray();

        return response()->json(['success' => true, 'data' => $allData]);
    }

    /**
     * Insert a new column
     */
    public function insertColumn(Request $request)
    {
        $columnName = $request->input('name', 'عمود جديد');
        $position = $request->input('position');
        
        $table = $this->getCurrentTable();
        $columns = $table->column_headers ?? [];
        
        if ($position === null) {
            $columns[] = $columnName;
        } else {
            array_splice($columns, $position, 0, [$columnName]);
        }
        
        $table->update(['column_headers' => $columns]);

        // Update all data records
        $records = $table->records()->get();
        foreach ($records as $record) {
            $rowData = $record->data;
            if ($position === null) {
                $rowData[] = '';
            } else {
                array_splice($rowData, $position, 0, ['']);
            }
            $record->update(['data' => $rowData]);
        }
        
        $allData = $table->records()->orderBy('sequence')->get()->pluck('data')->toArray();

        return response()->json(['success' => true, 'columns' => $columns, 'data' => $allData]);
    }

    /**
     * Delete a column
     */
    public function deleteColumn(Request $request)
    {
        $index = $request->input('index');
        $table = $this->getCurrentTable();
        $columns = $table->column_headers ?? [];
        
        if (isset($columns[$index])) {
            array_splice($columns, $index, 1);
            $table->update(['column_headers' => $columns]);

            // Update all records
            $records = $table->records()->get();
            foreach ($records as $record) {
                $rowData = $record->data;
                array_splice($rowData, $index, 1);
                $record->update(['data' => $rowData]);
            }
        }
        
        $allData = $table->records()->orderBy('sequence')->get()->pluck('data')->toArray();

        return response()->json(['success' => true, 'columns' => $columns, 'data' => $allData]);
    }

    /**
     * Rename a column
     */
    public function renameColumn(Request $request)
    {
        $index = $request->input('index');
        $newName = $request->input('name');
        
        $table = $this->getCurrentTable();
        $columns = $table->column_headers ?? [];
        
        if (isset($columns[$index])) {
            $columns[$index] = $newName;
            $table->update(['column_headers' => $columns]);
        }
        
        return response()->json(['success' => true, 'columns' => $columns]);
    }

    /**
     * Add sequential numbering to records
     */
    public function numberRecords(Request $request)
    {
        $startNumber = $request->input('start', 1);
        $columnIndex = $request->input('column_index', 0);
        
        $table = $this->getCurrentTable();
        $records = $table->records()->orderBy('sequence')->get();
        
        foreach ($records as $i => $record) {
            $rowData = $record->data;
            if (isset($rowData[$columnIndex])) {
                $rowData[$columnIndex] = $startNumber + $i;
                $record->update(['data' => $rowData]);
            }
        }
        
        $allData = $records->pluck('data')->toArray();

        return response()->json(['success' => true, 'data' => $allData]);
    }

    /**
     * Remove spaces from cells
     */
    public function removeSpaces(Request $request)
    {
        $columnIndex = $request->input('column_index'); // null means all columns
        $mode = $request->input('mode', 'trim'); // trim, all, extra
        
        $table = $this->getCurrentTable();
        $records = $table->records()->get();
        
        foreach ($records as $record) {
            $rowData = $record->data;
            $changed = false;

            foreach ($rowData as $i => &$cell) {
                if ($columnIndex !== null && $i != $columnIndex) continue;
                
                if (is_string($cell)) {
                    $original = $cell;
                    switch ($mode) {
                        case 'all':
                            $cell = str_replace(' ', '', $cell);
                            break;
                        case 'extra':
                            $cell = preg_replace('/\s+/', ' ', trim($cell));
                            break;
                        default:
                            $cell = trim($cell);
                    }
                    if ($original !== $cell) $changed = true;
                }
            }
            if ($changed) {
                $record->update(['data' => $rowData]);
            }
        }
        
        $allData = $table->records()->orderBy('sequence')->get()->pluck('data')->toArray();

        return response()->json(['success' => true, 'data' => $allData]);
    }

    /**
     * Find and replace text in column
     */
    public function replaceText(Request $request)
    {
        $columnIndex = $request->input('column_index');
        $find = $request->input('find');
        $replace = $request->input('replace', '');
        
        $table = $this->getCurrentTable();
        $records = $table->records()->get();
        $count = 0;
        
        foreach ($records as $record) {
            $rowData = $record->data;
            if (isset($rowData[$columnIndex]) && is_string($rowData[$columnIndex])) {
                $original = $rowData[$columnIndex];
                $rowData[$columnIndex] = str_replace($find, $replace, $rowData[$columnIndex]);
                if ($original !== $rowData[$columnIndex]) {
                    $record->update(['data' => $rowData]);
                    $count++;
                }
            }
        }
        
        $allData = $table->records()->orderBy('sequence')->get()->pluck('data')->toArray();

        return response()->json([
            'success' => true,
            'data' => $allData,
            'replaced_count' => $count,
            'message' => "تم استبدال $count قيمة"
        ]);
    }

    /**
     * Update cell data
     */
    public function updateCell(Request $request)
    {
        $rowIndex = $request->input('row'); // This is sequence
        $colIndex = $request->input('col');
        $value = $request->input('value');
        
        $table = $this->getCurrentTable();
        $record = $table->records()->where('sequence', $rowIndex)->first();
        
        if ($record) {
            $rowData = $record->data;
            if (isset($rowData[$colIndex])) {
                $rowData[$colIndex] = $value;
                $record->update(['data' => $rowData]);
            }
        }

        return response()->json(['success' => true]);
    }

    /**
     * Get current data
     */
    public function getData()
    {
        $table = $this->getCurrentTable();
        return response()->json([
            'columns' => $table->column_headers ?? [],
            'data' => $table->records()->orderBy('sequence')->get()->pluck('data')->toArray()
        ]);
    }

    /**
     * Export data to Excel
     */
    public function export()
    {
        $table = $this->getCurrentTable();
        $columns = $table->column_headers ?? [];
        $rows = $table->records()->orderBy('sequence')->get()->pluck('data')->toArray();
        
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setRightToLeft(true);
        
        // Write headers
        foreach ($columns as $col => $header) {
            $sheet->setCellValueByColumnAndRow($col + 1, 1, $header);
        }
        
        // Write data
        foreach ($rows as $rowIndex => $row) {
            foreach ($row as $colIndex => $cell) {
                if (is_array($cell)) $cell = implode(', ', $cell);
                $sheet->setCellValueByColumnAndRow($colIndex + 1, $rowIndex + 2, $cell);
            }
        }
        
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $fileName = 'data_export_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        return response()->streamDownload(function() use ($writer) {
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ]);
    }
}
