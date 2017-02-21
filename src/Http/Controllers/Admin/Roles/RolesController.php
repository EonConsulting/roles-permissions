<?php
/**
 * Created by PhpStorm.
 * User: jharing10
 * Date: 2017/02/20
 * Time: 5:49 PM
 */

namespace EONConsulting\RolesPermissions\Http\Controllers\Admin\Roles;


use App\Http\Controllers\Controller;
use EONConsulting\RolesPermissions\Http\Requests\StoreRoleRequest;
use EONConsulting\RolesPermissions\Http\Requests\UpdateRoleRequest;
use EONConsulting\RolesPermissions\Models\Permission;
use EONConsulting\RolesPermissions\Models\Role;

class RolesController extends Controller {

    public function index() {
        $roles = Role::with('permissions')->with('users')->get();

        return view('eon.roles::roles', ['roles' => $roles]);
    }

    public function show(Role $role) {
        $permissions = $role->permissions;
        $unheld = Permission::whereNotIn('id', $role->permissions()->pluck('id')->toArray())->get()->pluck('name', 'id');
        $all_permissions = Permission::get()->pluck('name', 'id');
        return view('eon.roles::role', ['role' => $role, 'permissions' => $permissions, 'unheld' => $unheld, 'all_permissions' => $all_permissions]);
    }

    public function create() {
        return view('eon.roles::create-role');
    }

    public function store(StoreRoleRequest $request) {
        $role = new Role;
        $role->name = $request->name;
        $role->slug = str_slug($request->name);

        $role->save();

        return response()->json(['success' => true]);
    }

    public function update_role(UpdateRoleRequest $request, Role $role) {
        $role->name = $request->name;
        $role->slug = str_slug($request->name);

        $role->save();

        return response()->json(['success' => true]);
    }

    public function update(Role $role, Permission $permission) {
        if($role->hasPermission($permission)) {
            $role->permissions()->detach($permission);
        } else {
            $role->permissions()->attach($permission);
        }

        return response()->json(['success' => 'true']);
    }

    public function destroy(Role $role) {
        if($role->users()->count() > 0) {
            return response()->json(['success' => false, 'error_messages' => 'There are users linked to that role.']);
        }

        $role->delete();

        return response()->json(['success' => true]);
    }

}