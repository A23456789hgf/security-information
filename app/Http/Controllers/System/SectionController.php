<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections = Section::withCount('pages')->orderBy('order_column')->get();

        return view('system.sections.index', compact('sections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sections = Section::orderBy('order_column')->get();
        return view('system.sections.create', compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:sections,name',
            'icon' => 'nullable|string|max:255',
            'position_type' => 'nullable|in:at_end,before,after',
            'relative_section_id' => 'nullable|exists:sections,id',
            'is_active' => 'boolean',
            'show_in_sidebar' => 'boolean',
        ], [
            'name.required' => 'اسم القسم مطلوب.',
            'name.unique' => 'اسم هذا القسم موجود مسبقاً في النظام (يمنع التكرار).',
        ]);

        $data = $request->except(['position_type', 'relative_section_id']);
        $data['slug'] = Str::slug($request->name) ?: 'sec-'.Str::random(5);
        $data['is_active'] = $request->has('is_active');
        $data['show_in_sidebar'] = $request->has('show_in_sidebar');

        // Handle Positioning
        $orderColumn = Section::max('order_column') + 1;
        $positionType = $request->input('position_type', 'at_end');
        $relativeSectionId = $request->input('relative_section_id');

        if (($positionType === 'before' || $positionType === 'after') && $relativeSectionId) {
            $relativeSection = Section::find($relativeSectionId);
            if ($relativeSection) {
                if ($positionType === 'before') {
                    $orderColumn = $relativeSection->order_column;
                    Section::where('order_column', '>=', $orderColumn)->increment('order_column');
                } else {
                    $orderColumn = $relativeSection->order_column + 1;
                    Section::where('order_column', '>=', $orderColumn)->increment('order_column');
                }
            }
        }
        
        $data['order_column'] = $orderColumn;
        Section::create($data);

        return redirect()->route('system.sections.index')->with('success', 'تم إنشاء القسم بنجاح');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Section $section)
    {
        $sections = Section::where('id', '!=', $section->id)->orderBy('order_column')->get();
        return view('system.sections.edit', compact('section', 'sections'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Section $section)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:sections,name,'.$section->id,
            'icon' => 'nullable|string|max:255',
            'position_type' => 'nullable|in:no_change,at_end,before,after',
            'relative_section_id' => 'nullable|exists:sections,id',
            'is_active' => 'boolean',
            'show_in_sidebar' => 'boolean',
        ], [
            'name.required' => 'اسم القسم مطلوب.',
            'name.unique' => 'اسم هذا القسم موجود مسبقاً في النظام (يمنع التكرار).',
        ]);

        $data = $request->except(['position_type', 'relative_section_id']);
        $data['slug'] = Str::slug($request->name) ?: $section->slug;
        $data['is_active'] = $request->has('is_active');
        $data['show_in_sidebar'] = $request->has('show_in_sidebar');

        // Handle Positioning
        $positionType = $request->input('position_type', 'no_change');
        $relativeSectionId = $request->input('relative_section_id');

        if ($positionType !== 'no_change') {
            if ($positionType === 'at_end') {
                $data['order_column'] = Section::max('order_column') + 1;
            } elseif (($positionType === 'before' || $positionType === 'after') && $relativeSectionId) {
                $relativeSection = Section::find($relativeSectionId);
                if ($relativeSection) {
                    if ($positionType === 'before') {
                        $orderColumn = $relativeSection->order_column;
                        Section::where('order_column', '>=', $orderColumn)->increment('order_column');
                    } else {
                        $orderColumn = $relativeSection->order_column + 1;
                        Section::where('order_column', '>=', $orderColumn)->increment('order_column');
                    }
                    $data['order_column'] = $orderColumn;
                }
            }
        }

        $section->update($data);

        return redirect()->route('system.sections.index')->with('success', 'تم تحديث القسم بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Section $section)
    {
        if ($section->pages()->count() > 0) {
            return back()->with('error', 'لا يمكن حذف القسم لأنه يحتوي على صفحات مرتبطة به');
        }

        $section->delete();

        return redirect()->route('system.sections.index')->with('success', 'تم حذف القسم بنجاح');
    }

    /**
     * Print sections listing.
     */
    public function print()
    {
        $sections = Section::withCount('pages')->orderBy('order_column')->get();
        return view('system.sections.print', compact('sections'));
    }
}
