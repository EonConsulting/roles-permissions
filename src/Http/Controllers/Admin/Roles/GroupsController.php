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
use EONConsulting\RolesPermissions\Models\Group;
use EONConsulting\RolesPermissions\Models\Role;

class GroupsController extends Controller {

    public function index() {
        $groups = Group::get();

        $breadcrumbs = [
            'title' => 'Roles and Permissions',
            'child' => [
                'title' => 'Groups'
            ]
        ];

        return view('eon.roles::groups', ['groups' => $groups], ['breadcrumbs' => $breadcrumbs]);
    }

    public function show(Group $group) {
        $users = $group->users_roles;
        $all_roles = Role::get()->pluck('name', 'id')->toArray();

        $user_data = [
            'users' => $users,
            'group' => $group,
            'roles' => $all_roles,
        ];

        $breadcrumbs = [
            'title' => 'Roles and Permissions',
            'child' => [
                'title' => 'Groups',
                'href' => route("eon.admin.groups"),
                'child' => [
                    'title' => $group->name
                ]
            ]
        ];

        return view('eon.roles::group', $user_data, ['breadcrumbs' => $breadcrumbs]);
    }

    public function create() {

        $breadcrumbs = [
            'title' => 'Roles and Permissions',
            'child' => [
                'title' => 'Groups',
                'href' => route("eon.admin.groups"),
                'child' => [
                    'title' => 'Create a Group'
                ]
            ]
        ];

        return view('eon.roles::create-group', ['breadcrumbs' => $breadcrumbs]);
    }

    public function store(StoreDepartmentRequest $request) {
        $group = new Group;
        $group->name = $request->name;
        $group->slug = str_slug($request->name);

        $group->save();

        return response()->json(['success' => true]);
    }

    public function update(UpdateDepartmentRequest $request, Group $group) {
        $group->name = $request->name;
        $group->save();

        return response()->json(['success' => true]);
    }

    public function destroy(Group $group) {
        if($group->users_roles()->count() > 0 || $group->users_permissions()->count() > 0) {
            return response()->json(['success' => false, 'error_messages' => 'There are users linked to that group.']);
        }

        $group->delete();

        return response()->json(['success' => true]);
    }

}
