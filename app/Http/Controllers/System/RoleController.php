<?php

namespace App\Http\Controllers\System;

use App\Helpers\PermissionHelper;
use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();

        return view('system.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = PermissionHelper::getGrouped();

        return view('system.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'required|array|min:1',
            'scopes' => 'nullable|array',
            'scopes.*' => 'in:all,own,none',
        ]);

        DB::transaction(function () use ($request) {
            $role = Role::create([
                'name' => $request->name,
                'guard_name' => 'web',
                'scopes' => $request->scopes ?? [],
            ]);
            $role->syncPermissions($request->permissions);
        });

        return redirect()->route('system.roles.index')->with('success', 'تم إنشاء الدور بنجاح');
    }

    public function edit(Role $role)
    {
        $permissions = PermissionHelper::getGrouped();
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        $roleScopes = is_array($role->scopes) ? $role->scopes : (json_decode($role->scopes, true) ?? []);

        return view('system.roles.edit', compact('role', 'permissions', 'rolePermissions', 'roleScopes'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,'.$role->id,
            'permissions' => 'required|array|min:1',
            'scopes' => 'nullable|array',
            'scopes.*' => 'in:all,own,none',
        ]);

        DB::transaction(function () use ($request, $role) {
            $role->update([
                'name' => $request->name,
                'scopes' => $request->scopes ?? [],
            ]);
            $role->syncPermissions($request->permissions);
        });

        return redirect()->route('system.roles.index')->with('success', 'تم تحديث الدور بنجاح');
    }

    public function destroy(Role $role)
    {
        if ($role->name === 'super-admin') {
            return back()->with('error', 'لا يمكن حذف دور المسؤول العام');
        }
        $role->delete();

        return back()->with('success', 'تم حذف الدور بنجاح');
    }
}
