<?php
/**
 * Created by PhpStorm.
 * User: jharing10
 * Date: 2017/02/20
 * Time: 5:56 PM
 */

namespace EONConsulting\RolesPermissions\Http\Controllers\Admin\Roles;


use App\Http\Controllers\Controller;
use EONConsulting\RolesPermissions\Http\Requests\StorePermissionRequest;
use EONConsulting\RolesPermissions\Http\Requests\UpdatePermissionRequest;
use EONConsulting\RolesPermissions\Models\Permission;

class PermissionsController extends Controller {

    public function index() {
        $permissions = Permission::with('roles')->with('users')->get();

        $breadcrumbs = [
            'title' => 'Roles and Permissions',
            'child' => [
                'title' => 'Permissions'
            ]
        ];

        return view('eon.roles::permissions', ['permissions' => $permissions], ['breadcrumbs' => $breadcrumbs]);
    }

    public function show(Permission $permission) {

        $breadcrumbs = [
            'title' => 'Roles and Permissions',
            'child' => [
                'title' => 'Permissions',
                'href' => route("eon.admin.permissions"),
                'child' => [
                    'title' => $permission->name
                ]
            ]
        ];

        return view('eon.roles::permission', ['permission' => $permission], ['breadcrumbs' => $breadcrumbs]);
    }

    public function create() {

        $breadcrumbs = [
            'title' => 'Roles and Permissions',
            'child' => [
                'title' => 'Permissions',
                'href' => route("eon.admin.permissions"),
                'child' => [
                    'title' => 'Create a Permission'
                ]
            ]
        ];

        return view('eon.roles::create-permission', ['breadcrumbs' => $breadcrumbs]);
    }

    public function store(StorePermissionRequest $request) {
        $permission = new Permission;
        $permission->name = $request->name;
        $permission->slug = str_slug($request->name);

        $permission->save();

        return response()->json(['success' => true]);
    }

    public function update(UpdatePermissionRequest $request, Permission $permission) {
        $permission->name = $request->name;
        $permission->slug = str_slug($request->name);

        $permission->save();

        return response()->json(['success' => true]);
    }

    public function destroy(Permission $permission) {
        if($permission->users()->count() > 0) {
            return response()->json(['success' => false, 'error_messages' => 'There are users linked to that permission.']);
        }

        $permission->delete();

        return response()->json(['success' => true]);
    }

}
