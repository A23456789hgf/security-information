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
        return view('system.sections.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:sections,name',
            'icon' => 'nullable|string|max:255',
            'order_column' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name) ?: 'sec-' . Str::random(5);
        $data['is_active'] = $request->has('is_active');

        Section::create($data);

        return redirect()->route('system.sections.index')->with('success', 'تم إنشاء القسم بنجاح');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Section $section)
    {
        return view('system.sections.edit', compact('section'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Section $section)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:sections,name,' . $section->id,
            'icon' => 'nullable|string|max:255',
            'order_column' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name) ?: $section->slug;
        $data['is_active'] = $request->has('is_active');

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
}
