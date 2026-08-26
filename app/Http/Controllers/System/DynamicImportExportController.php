<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Record;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Facades\Excel;

class DynamicImportExportController extends Controller
{
    public function importForm(Page $page)
    {
        return view('system.dynamic_data.import', compact('page'));
    }

    public function upload(Request $request, Page $page)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls,csv']);

        $path = $request->file('file')->store('temp_imports');
        $import = new class implements \Maatwebsite\Excel\Concerns\ToCollection
        {
            public $collection;

            public function collection(\Illuminate\Support\Collection $collection)
            {
                $this->collection = $collection;
            }
        };

        Excel::import($import, storage_path('app/'.$path));
        $rows = $import->collection;
        $headers = $rows->first()->toArray();
        $preview = $rows->slice(1, 5)->toArray();

        // Store temp info in session
        session(["dynamic_import_{$page->id}" => [
            'path' => $path,
            'headers' => $headers,
        ]]);

        return view('system.dynamic_data.import_mapping', compact('page', 'headers', 'preview'));
    }

    public function storeImport(Request $request, Page $page)
    {
        $sessionData = session("dynamic_import_{$page->id}");
        if (! $sessionData) {
            return redirect()->route('system.dynamic-data.show', $page->slug);
        }

        $mapping = $request->input('mapping'); // [field_id => header_index]
        $path = $sessionData['path'];

        $import = new class($page, $mapping) implements \Maatwebsite\Excel\Concerns\ToCollection
        {
            private $page;

            private $mapping;

            public function __construct($page, $mapping)
            {
                $this->page = $page;
                $this->mapping = $mapping;
            }

            public function collection(\Illuminate\Support\Collection $rows)
            {
                // Skip header
                $rows = $rows->slice(1);

                DB::transaction(function () use ($rows) {
                    foreach ($rows as $row) {
                        $record = Record::create([
                            'page_id' => $this->page->id,
                            'created_by' => auth()->id(),
                        ]);

                        foreach ($this->mapping as $fieldId => $headerIndex) {
                            if ($headerIndex === null || $headerIndex === '') {
                                continue;
                            }

                            $record->values()->create([
                                'field_id' => $fieldId,
                                'value' => $row[$headerIndex] ?? null,
                            ]);
                        }
                    }
                });
            }
        };

        Excel::import($import, storage_path('app/'.$path));

        session()->forget("dynamic_import_{$page->id}");

        return redirect()->route('system.dynamic-data.show', $page->slug)->with('success', 'تم استيراد البيانات بنجاح');
    }

    public function export(Page $page)
    {
        $records = Record::where('page_id', $page->id)
            ->visibleToUserForModule('dynamic_pages')
            ->with(['values', 'user', 'entity'])
            ->get();

        $fields = $page->fields;

        return Excel::download(new class($records, $fields) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings, \Maatwebsite\Excel\Concerns\WithMapping
        {
            private $records;

            private $fields;

            public function __construct($records, $fields)
            {
                $this->records = $records;
                $this->fields = $fields;
            }

            public function collection()
            {
                return $this->records;
            }

            public function headings(): array
            {
                $headings = $this->fields->pluck('label')->toArray();
                $headings[] = 'بواسطة';
                $headings[] = 'تاريخ الإضافة';

                return $headings;
            }

            public function map($record): array
            {
                $values = $record->values->pluck('value', 'field_id');
                $row = [];
                foreach ($this->fields as $field) {
                    $row[] = $values[$field->id] ?? '-';
                }
                $row[] = $record->user->name ?? 'مجهول';
                $row[] = $record->created_at->format('Y-m-d');

                return $row;
            }
        }, "export_{$page->slug}.xlsx");
    }

    public function template(Page $page)
    {
        $fields = $page->fields->pluck('label')->toArray();

        return Excel::download(new class($fields) implements FromArray
        {
            private $fields;

            public function __construct($fields)
            {
                $this->fields = $fields;
            }

            public function array(): array
            {
                return [$this->fields];
            }
        }, "template_{$page->slug}.xlsx");
    }
}
