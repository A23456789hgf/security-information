<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\InternalEntity;
use App\Models\Governorate;
use App\Models\Directorate;
use Illuminate\Http\Request;

class EntityController extends Controller
{
    public function index()
    {
        $entities = InternalEntity::with('parent', 'governorate', 'directorate')->paginate(20);
        return view('system.entities.index', compact('entities'));
    }

    public function create()
    {
        $parents = InternalEntity::all();
        $governorates = Governorate::all();
        $directorates = Directorate::all();
        return view('system.entities.create', compact('parents', 'governorates', 'directorates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:internal_entities,id',
            'governorate_id' => 'nullable|exists:governorates,id',
            'directorate_id' => 'nullable|exists:directorates,id',
        ]);

        InternalEntity::create($request->all());

        return redirect()->route('system.entities.index')->with('success', 'تم إضافة الجهة بنجاح');
    }

    public function show(InternalEntity $entity)
    {
        $entity->load('parent', 'children', 'governorate', 'directorate', 'users');
        return view('system.entities.show', compact('entity'));
    }

    public function edit(InternalEntity $entity)
    {
        $parents = InternalEntity::where('id', '!=', $entity->id)->get();
        $governorates = Governorate::all();
        $directorates = Directorate::all();
        return view('system.entities.edit', compact('entity', 'parents', 'governorates', 'directorates'));
    }

    public function update(Request $request, InternalEntity $entity)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:internal_entities,id|different:id',
            'governorate_id' => 'nullable|exists:governorates,id',
            'directorate_id' => 'nullable|exists:directorates,id',
        ]);

        $entity->update($request->all());

        return redirect()->route('system.entities.index')->with('success', 'تم تحديث بيانات الجهة بنجاح');
    }

    public function destroy(InternalEntity $entity)
    {
        if ($entity->children()->count() > 0) {
            return back()->with('error', 'لا يمكن حذف هذه الجهة لوجود جهات تابعة لها');
        }

        $entity->delete();
        return redirect()->route('system.entities.index')->with('success', 'تم حذف الجهة بنجاح');
    }
}
