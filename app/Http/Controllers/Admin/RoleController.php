<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:employee');
        $this->middleware('permission:manage_roles');
    }

    public function index()
    {
        $roles = Role::withCount(['permissions', 'users'])->get();
        $permissions = Permission::all()->groupBy(function($permission) {
            return explode('_', $permission->name)[0] ?? 'other';
        });

        return view('admin.roles.index', compact('roles', 'permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role = Role::create(['name' => $request->name]);
        $role->givePermissionTo($request->permissions);

        activity()
            ->performedOn($role)
            ->causedBy(auth()->guard('employee')->user())
            ->log('created role');

        return response()->json([
            'success' => true,
            'message' => 'Role created successfully.'
        ]);
    }

    public function show($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $users = Employee::role($role->name)->paginate(20);

        return view('admin.roles.show', compact('role', 'users'));
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'name' => 'required|string|unique:roles,name,' . $id,
            'permissions' => 'required|array',
        ]);

        $role->name = $request->name;
        $role->save();

        $role->syncPermissions($request->permissions);

        activity()
            ->performedOn($role)
            ->causedBy(auth()->guard('employee')->user())
            ->log('updated role');

        return response()->json([
            'success' => true,
            'message' => 'Role updated successfully.'
        ]);
    }

    public function assignRole(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'role' => 'required|exists:roles,name',
        ]);

        $employee = Employee::findOrFail($request->employee_id);
        $employee->syncRoles([$request->role]);

        activity()
            ->performedOn($employee)
            ->causedBy(auth()->guard('employee')->user())
            ->withProperties(['role' => $request->role])
            ->log('assigned role to employee');

        return response()->json([
            'success' => true,
            'message' => 'Role assigned successfully.'
        ]);
    }
}
