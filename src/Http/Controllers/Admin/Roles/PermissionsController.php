<?php
/**
 * Created by PhpStorm.
 * User: jharing10
 * Date: 2017/02/20
 * Time: 5:56 PM
 */

namespace EONConsulting\RolesPermissions\Http\Controllers\Admin\Roles;


use App\Http\Controllers\Controller;
use EONConsulting\RolesPermissions\Http\Requests\UpdatePermissionRequest;
use EONConsulting\RolesPermissions\Models\Permission;

class PermissionsController extends Controller {

    public function index() {
        $permissions = Permission::with('roles')->with('users')->get();

        return view('eon.roles::permissions', ['permissions' => $permissions]);
    }

    public function show(Permission $permission) {
        return view('eon.roles::permission', ['permission' => $permission]);
    }

    public function update(UpdatePermissionRequest $request, Permission $permission) {
        $permission->name = $request->name;
        $permission->slug = str_slug($request->name);

        $permission->save();

        return response()->json(['success' => true]);
    }

}