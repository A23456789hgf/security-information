<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Record;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DynamicDataController extends Controller
{
    public function show(Page $page)
    {
        if (!$page->is_active) {
            abort(404);
        }

        $records = Record::where('page_id', $page->id)
            ->visibleToUserForModule('dynamic_pages')
            ->with(['values', 'user', 'entity'])
            ->latest()
            ->paginate(20);

        return view('system.dynamic_data.index', compact('page', 'records'));
    }

    public function create(Page $page)
    {
        $page->load('fields');
        return view('system.dynamic_data.create', compact('page'));
    }

    public function store(Request $request, Page $page)
    {
        $fields = $page->fields;
        $rules = [];
        foreach ($fields as $field) {
            if ($field->is_required) {
                $rules['fields.' . $field->id] = 'required';
            }
        }

        $request->validate($rules);

        DB::transaction(function () use ($request, $page, $fields) {
            $record = Record::create([
                'page_id' => $page->id,
                'created_by' => auth()->id(),
            ]);

            foreach ($request->input('fields', []) as $fieldId => $value) {
                $field = $fields->find($fieldId);
                
                if ($field->type === 'file' && $request->hasFile("fields.{$fieldId}")) {
                    $value = $request->file("fields.{$fieldId}")->store('dynamic_files', 'public');
                }

                $record->values()->create([
                    'field_id' => $fieldId,
                    'value' => is_array($value) ? json_encode($value) : $value,
                ]);
            }
        });

        return redirect()->route('system.dynamic-data.show', $page->slug)
            ->with('success', 'تم حفظ السجل بنجاح');
    }

    public function edit(Page $page, Record $record)
    {
        $page->load('fields');
        $record->load('values');
        $values = $record->values->pluck('value', 'field_id')->toArray();

        return view('system.dynamic_data.edit', compact('page', 'record', 'values'));
    }

    public function update(Request $request, Page $page, Record $record)
    {
        $fields = $page->fields;
        $rules = [];
        foreach ($fields as $field) {
            if ($field->is_required) {
                $rules['fields.' . $field->id] = 'required';
            }
        }

        $request->validate($rules);

        DB::transaction(function () use ($request, $record, $fields) {
            foreach ($request->input('fields', []) as $fieldId => $value) {
                $field = $fields->find($fieldId);

                if ($field->type === 'file' && $request->hasFile("fields.{$fieldId}")) {
                    $value = $request->file("fields.{$fieldId}")->store('dynamic_files', 'public');
                }

                $record->values()->updateOrCreate(
                    ['field_id' => $fieldId],
                    ['value' => is_array($value) ? json_encode($value) : $value]
                );
            }
        });

        return redirect()->route('system.dynamic-data.show', $page->slug)
            ->with('success', 'تم تحديث السجل بنجاح');
    }

    public function destroy(Page $page, Record $record)
    {
        $record->delete();
        return back()->with('success', 'تم حذف السجل بنجاح');
    }
}
