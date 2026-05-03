<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\InternalEntity;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles', 'internal_entity')->paginate(20);
        return view('system.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        $entities = InternalEntity::all();
        return view('system.users.create', compact('roles', 'entities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:1|confirmed',
            'role_id' => 'required|exists:roles,id',
            'internal_entity_id' => 'nullable|exists:internal_entities,id',
            'phone' => 'nullable|string|max:20',
            'work_status' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'internal_entity_id' => $request->internal_entity_id,
            'phone' => $request->phone,
            'work_status' => $request->work_status,
            'is_active' => $request->has('is_active'),
        ]);

        $user->assignRole($request->role_id);

        return redirect()->route('system.users.index')->with('success', 'تم إنشاء المستخدم بنجاح');
    }

    public function show(User $user)
    {
        $user->load('roles', 'internal_entity');
        return view('system.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $entities = InternalEntity::all();
        $userRole = $user->roles->first() ? $user->roles->first()->id : null;
        return view('system.users.edit', compact('user', 'roles', 'entities', 'userRole'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:1|confirmed',
            'role_id' => 'required|exists:roles,id',
            'internal_entity_id' => 'nullable|exists:internal_entities,id',
            'phone' => 'nullable|string|max:20',
            'work_status' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $data = [
            'name' => $request->name,
            'username' => $request->username,
            'internal_entity_id' => $request->internal_entity_id,
            'phone' => $request->phone,
            'work_status' => $request->work_status,
            'is_active' => $request->has('is_active'),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        $user->syncRoles([$request->role_id]);

        return redirect()->route('system.users.index')->with('success', 'تم تحديث بيانات المستخدم بنجاح');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'لا يمكنك حذف نفسك');
        }

        $user->delete();
        return redirect()->route('system.users.index')->with('success', 'تم حذف المستخدم بنجاح');
    }
}
