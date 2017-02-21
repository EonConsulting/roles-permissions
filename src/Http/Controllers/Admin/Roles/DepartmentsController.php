<?php
/**
 * Created by PhpStorm.
 * User: jharing10
 * Date: 2017/02/21
 * Time: 6:35 PM
 */

namespace EONConsulting\RolesPermissions\Http\Controllers\Admin\Roles;


use App\Http\Controllers\Controller;
use EONConsulting\RolesPermissions\Http\Requests\StoreDepartmentRequest;
use EONConsulting\RolesPermissions\Http\Requests\UpdateDepartmentRequest;
use EONConsulting\RolesPermissions\Models\Department;
use EONConsulting\RolesPermissions\Models\Role;

class DepartmentsController extends Controller {

    public function index() {
        $departments = Department::get();

        return view('eon.roles::departments', ['departments' => $departments]);
    }

    public function show(Department $department) {
        $users = $department->users_roles;
        $all_roles = Role::get()->pluck('name', 'id')->toArray();
        return view('eon.roles::department', ['users' => $users, 'department' => $department, 'roles' => $all_roles]);
    }

    public function create() {
        return view('eon.roles::create-department');
    }

    public function store(StoreDepartmentRequest $request) {
        $department = new Department;
        $department->name = $request->name;
        $department->slug = str_slug($request->name);

        $department->save();

        return response()->json(['success' => true]);
    }

    public function update(UpdateDepartmentRequest $request, Department $department) {
        $department->name = $request->name;
        $department->save();

        return response()->json(['success' => true]);
    }

    public function destroy(Department $department) {
        if($department->users_roles()->count() > 0 || $department->users_permissions()->count() > 0) {
            return response()->json(['success' => false, 'error_messages' => 'There are users linked to that department.']);
        }

        $department->delete();

        return response()->json(['success' => true]);
    }

}