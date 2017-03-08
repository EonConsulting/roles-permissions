<?php
/**
 * Created by PhpStorm.
 * User: jharing10
 * Date: 2017/02/20
 * Time: 5:43 PM
 */

namespace EONConsulting\RolesPermissions\Http\Controllers\Admin\Roles;


use App\Http\Controllers\Controller;
use App\Models\User;
use EONConsulting\RolesPermissions\Models\Department;
use EONConsulting\RolesPermissions\Models\Group;
use EONConsulting\RolesPermissions\Models\Permission;
use EONConsulting\RolesPermissions\Models\Role;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller {

    public function index() {
        $users = User::get();

        return view('eon.roles::users', ['users' => $users]);
    }

    public function show(User $user) {
        $groups = Group::get()->pluck('name', 'id');
        $roles = $user->roles;
        $unheld = Role::whereNotIn('id', $user->roles()->pluck('id')->toArray())->get()->pluck('name', 'id');
        $all_roles = Role::get()->pluck('name', 'id');

        return view('eon.roles::user', ['user' => $user, 'roles' => $roles, 'held' => $roles->keyBy('id'), 'all_roles' => $all_roles, 'unheld' => $unheld, 'groups' => $groups]);
    }

    public function update(User $user, Role $role, Group $group) {
        $obj = DB::table('users_roles')->where('user_id', $user->id)->where('role_id', $role->id)->where('group_id', $group->id)->first();
        if ($obj) {
            DB::table('users_roles')->where('user_id', $user->id)->where('role_id', $role->id)->where('group_id', $group->id)->delete();
        } else {
            DB::table('users_roles')->insert(['user_id' => $user->id, 'role_id' => $role->id, 'group_id' => request()->get('group_id')]);
        }

        return response()->json(['success' => true]);
    }

}