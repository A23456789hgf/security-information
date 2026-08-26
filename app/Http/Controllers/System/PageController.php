<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\Field;
use App\Models\Page;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::withCount('fields')->get();

        return view('system.pages.index', compact('pages'));
    }

    public function create()
    {
        $sections = Section::where('is_active', true)->get();
        $pages = Page::where('is_active', true)->get();

        return view('system.pages.create', compact('sections', 'pages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'section_id' => 'required|exists:sections,id',
            'is_active' => 'boolean',
            'is_unique_id' => 'boolean',
            'fields' => 'required|array|min:1',
            'fields.*.label' => 'required|string|max:255',
            'fields.*.type' => 'required|string|in:text,number,textarea,select,date,datetime,checkbox,link,table,file,image',
            'fields.*.options' => 'nullable|string',
            'fields.*.metadata' => 'nullable|array',
        ]);

        $pageData = $request->only(['name', 'section_id', 'is_active', 'is_unique_id']);
        $pageData['slug'] = $this->generateEnglishSlug($request->name);

        // Ensure slug is unique
        $originalSlug = $pageData['slug'];
        $count = 1;
        while (Page::where('slug', $pageData['slug'])->exists()) {
            $pageData['slug'] = $originalSlug.'-'.$count++;
        }

        $page = Page::create($pageData);

        foreach ($request->fields as $index => $fieldData) {
            $data = [
                'label' => $fieldData['label'],
                'name' => $this->generateEnglishSlug($fieldData['label']),
                'type' => $fieldData['type'],
                'is_required' => isset($fieldData['is_required']),
                'order_column' => $index,
                'metadata' => $fieldData['metadata'] ?? null,
            ];

            if (! empty($fieldData['options'])) {
                $options = array_map('trim', explode(',', $fieldData['options']));
                $data['options'] = $options;
            }

            $page->fields()->create($data);
        }

        return redirect()->route('system.pages.index')->with('success', 'تم إنشاء الصفحة وحقولها بنجاح');
    }

    private function generateEnglishSlug($text)
    {
        $map = [
            'ا' => 'a', 'ب' => 'b', 'ت' => 't', 'ث' => 'th', 'ج' => 'j', 'ح' => 'h', 'خ' => 'kh',
            'د' => 'd', 'ذ' => 'dh', 'ر' => 'r', 'ز' => 'z', 'س' => 's', 'ش' => 'sh', 'ص' => 's',
            'ض' => 'd', 'ط' => 't', 'ظ' => 'z', 'ع' => 'a', 'غ' => 'gh', 'ف' => 'f', 'ق' => 'q',
            'ك' => 'k', 'ل' => 'l', 'م' => 'm', 'ن' => 'n', 'ه' => 'h', 'و' => 'w', 'ي' => 'y',
            'ة' => 'h', 'أ' => 'a', 'إ' => 'e', 'ؤ' => 'o', 'ئ' => 'e', 'ى' => 'a', 'لا' => 'la',
        ];

        $english = strtr($text, $map);

        return Str::slug($english);
    }

    public function edit(Page $page)
    {
        $page->load('fields');
        $sections = Section::where('is_active', true)->get();

        return view('system.pages.edit', compact('page', 'sections'));
    }

    public function update(Request $request, Page $page)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'section_id' => 'required|exists:sections,id',
            'is_active' => 'boolean',
            'is_unique_id' => 'boolean',
        ]);

        $data = $request->only(['name', 'section_id', 'is_active', 'is_unique_id']);
        $data['slug'] = $this->generateEnglishSlug($request->name);

        // Ensure slug is unique if it changed
        if ($data['slug'] !== $page->slug) {
            $originalSlug = $data['slug'];
            $count = 1;
            while (Page::where('slug', $data['slug'])->where('id', '!=', $page->id)->exists()) {
                $data['slug'] = $originalSlug.'-'.$count++;
            }
        }

        $page->update($data);

        return redirect()->route('system.pages.index')->with('success', 'تم تحديث الصفحة بنجاح');
    }

    public function destroy(Page $page)
    {
        $page->delete();

        return redirect()->route('system.pages.index')->with('success', 'تم حذف الصفحة بنجاح');
    }

    // Field Management
    public function storeField(Request $request, Page $page)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'type' => 'required|string|in:text,number,textarea,select,date,datetime,checkbox,link,table,file,image',
            'is_required' => 'boolean',
            'options' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['name'] = $this->generateEnglishSlug($request->label);

        if ($request->filled('options')) {
            $options = array_map('trim', explode(',', $request->options));
            $data['options'] = $options;
        }

        $page->fields()->create($data);

        return back()->with('success', 'تم إضافة الحقل بنجاح');
    }

    public function updateField(Request $request, Field $field)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'type' => 'required|string|in:text,number,textarea,select,date,datetime,checkbox,link,table,file,image',
            'is_required' => 'boolean',
            'options' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['name'] = $this->generateEnglishSlug($request->label);

        if ($request->filled('options')) {
            $options = array_map('trim', explode(',', $request->options));
            $data['options'] = $options;
        }

        $field->update($data);

        return back()->with('success', 'تم تحديث الحقل بنجاح');
    }

    public function destroyField(Field $field)
    {
        $field->delete();

        return back()->with('success', 'تم حذف الحقل بنجاح');
    }
}
