<?php

namespace App\Http\Controllers\System;

use App\Exports\UniversalExport;
use App\Http\Controllers\Controller;
use App\Models\Directorate;
use App\Models\Governorate;
use App\Models\InternalEntity;
use App\Models\User;
use App\Models\UserGeographicScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['roles', 'internal_entity', 'geographicScopes.governorate', 'geographicScopes.directorate'])->paginate(20);

        return view('system.users.index', compact('users'));
    }

    public function export(Request $request)
    {
        $query = User::with(['roles', 'internal_entity', 'geographicScopes.governorate', 'geographicScopes.directorate']);

        $headings = [
            'ID',
            'الاسم',
            'اسم المستخدم',
            'الدور',
            'النطاق الإداري',
            'النطاق الجغرافي',
            'رقم الهاتف',
            'الحالة',
        ];

        return (new UniversalExport($query, $headings, function ($row) {
            $geoSummary = $row->geographicScopes->map(function($g) {
                if ($g->scope_type === 'all') return 'كل المحافظات والمديريات';
                if ($g->scope_type === 'none') return 'بدون محافظة';
                $gov = $g->governorate->name ?? 'غير محدد';
                $dir = $g->directorate->name ?? 'كل المديريات';
                return "{$gov} ({$dir})";
            })->implode(' | ');

            return [
                $row->id,
                $row->name,
                $row->username,
                $row->roles->pluck('name')->implode(', '),
                $row->internal_entity->name ?? 'عام / غير محدد',
                $geoSummary ?: 'غير محدد',
                $row->phone ?? '-',
                $row->is_active ? 'نشط' : 'معطل',
            ];
        }))->download('users_'.date('Y-m-d').'.xlsx');
    }

    public function create()
    {
        $roles = Role::all();
        $entities = UserScopeService::getScopedEntities();
        $governorates = UserScopeService::getScopedGovernorates();
        $directorates = UserScopeService::getScopedDirectorates();

        return view('system.users.create', compact('roles', 'entities', 'governorates', 'directorates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:users,name',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:1|confirmed',
            'role_id' => 'nullable|exists:roles,id',
            'roles' => 'nullable|array',
            'internal_entity_id' => 'nullable|exists:internal_entities,id',
            'phone' => 'nullable|string|max:20',
            'work_status' => 'nullable|string|max:255',
            'is_active' => 'nullable',
        ], [
            'name.required' => 'اسم المستخدم الكامل مطلوب.',
            'name.unique' => 'يوجد مستخدم آخر مسجل بنفس هذا الاسم الكامل (يمنع التكرار).',
            'username.required' => 'اسم الدخول (اسم المستخدم) مطلوب.',
            'username.unique' => 'اسم الدخول هذا مستخدم مسبقاً من قِبل حساب آخر (يمنع التكرار).',
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'internal_entity_id' => $request->internal_entity_id,
            'phone' => $request->phone,
            'work_status' => $request->work_status,
            'is_active' => $request->has('is_active') && ($request->is_active == '1' || $request->is_active == 'on' || $request->is_active === true),
        ]);

        if ($request->filled('roles')) {
            $user->syncRoles($request->roles);
        } elseif ($request->filled('role_id')) {
            $user->assignRole($request->role_id);
        }

        $this->saveGeographicScopes($user, $request);

        return redirect()->route('system.users.index')->with('success', 'تم إنشاء المستخدم بنجاح مع تحديد النطاق الإداري والجغرافي.');
    }

    public function show(User $user)
    {
        $user->load(['roles', 'internal_entity', 'geographicScopes.governorate', 'geographicScopes.directorate']);

        return view('system.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $user->load(['roles', 'internal_entity', 'geographicScopes']);
        $roles = Role::all();
        $entities = UserScopeService::getScopedEntities();
        $governorates = UserScopeService::getScopedGovernorates();
        $directorates = UserScopeService::getScopedDirectorates();
        $userRole = $user->roles->first() ? $user->roles->first()->id : null;

        return view('system.users.edit', compact('user', 'roles', 'entities', 'governorates', 'directorates', 'userRole'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:1|confirmed',
            'role_id' => 'nullable|exists:roles,id',
            'roles' => 'nullable|array',
            'internal_entity_id' => 'nullable|exists:internal_entities,id',
            'phone' => 'nullable|string|max:20',
            'work_status' => 'nullable|string|max:255',
            'is_active' => 'nullable',
        ], [
            'name.required' => 'اسم المستخدم الكامل مطلوب.',
            'name.unique' => 'يوجد مستخدم آخر مسجل بنفس هذا الاسم الكامل (يمنع التكرار).',
            'username.required' => 'اسم الدخول (اسم المستخدم) مطلوب.',
            'username.unique' => 'اسم الدخول هذا مستخدم مسبقاً من قِبل حساب آخر (يمنع التكرار).',
        ]);

        $data = [
            'name' => $request->name,
            'username' => $request->username,
            'internal_entity_id' => $request->internal_entity_id,
            'phone' => $request->phone,
            'work_status' => $request->work_status,
            'is_active' => $request->has('is_active') && ($request->is_active == '1' || $request->is_active == 'on' || $request->is_active === true),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        if ($request->filled('roles')) {
            $user->syncRoles($request->roles);
        } elseif ($request->filled('role_id')) {
            $user->syncRoles([$request->role_id]);
        }

        $this->saveGeographicScopes($user, $request);

        return redirect()->route('system.users.index')->with('success', 'تم تحديث بيانات المستخدم والنطاق الإداري والجغرافي بنجاح');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'لا يمكنك حذف نفسك');
        }

        $user->delete();

        return redirect()->route('system.users.index')->with('success', 'تم حذف المستخدم بنجاح');
    }

    public function printAll(Request $request)
    {
        $users = User::with(['roles', 'internal_entity', 'geographicScopes.governorate', 'geographicScopes.directorate'])->get();

        return view('system.users.print', [
            'isList' => true,
            'users' => $users
        ]);
    }

    public function print(User $user)
    {
        $user->load(['roles', 'internal_entity', 'geographicScopes.governorate', 'geographicScopes.directorate']);

        return view('system.users.print', [
            'isList' => false,
            'user' => $user
        ]);
    }

    private function saveGeographicScopes(User $user, Request $request)
    {
        $user->geographicScopes()->delete();

        if ($request->has('geographic_scopes') && is_array($request->geographic_scopes)) {
            foreach ($request->geographic_scopes as $scope) {
                $type = $scope['scope_type'] ?? 'specific';
                $govId = (!empty($scope['governorate_id']) && is_numeric($scope['governorate_id'])) ? (int)$scope['governorate_id'] : null;
                $dirId = (!empty($scope['directorate_id']) && is_numeric($scope['directorate_id'])) ? (int)$scope['directorate_id'] : null;

                if ($type === 'all' || $type === 'none') {
                    $govId = null;
                    $dirId = null;
                }

                if ($type === 'all' || $type === 'none' || $govId !== null || $dirId !== null) {
                    UserGeographicScope::create([
                        'user_id'        => $user->id,
                        'governorate_id' => $govId,
                        'directorate_id' => $dirId,
                        'scope_type'     => $type,
                    ]);
                }
            }
        }
    }
}
