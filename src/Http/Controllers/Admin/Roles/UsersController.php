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
use EONConsulting\RolesPermissions\Models\Permission;
use EONConsulting\RolesPermissions\Models\Role;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller {

    public function index() {
        $users = User::get();

        return view('eon.roles::users', ['users' => $users]);
    }

    public function show(User $user) {
        $all_departments = Department::get()->pluck('name', 'id');
        $roles = $user->roles;
        $unheld = Role::whereNotIn('id', $user->roles()->pluck('id')->toArray())->get()->pluck('name', 'id');
        $all_roles = Role::get()->pluck('name', 'id');

        return view('eon.roles::user', ['user' => $user, 'roles' => $roles, 'held' => $roles->keyBy('id'), 'all_roles' => $all_roles, 'unheld' => $unheld, 'departments' => $all_departments]);
    }

    public function update(User $user, Role $role, Department $department) {
        $obj = DB::table('users_roles')->where('user_id', $user->id)->where('role_id', $role->id)->where('department_id', $department->id)->first();
        if ($obj) {
            DB::table('users_roles')->where('user_id', $user->id)->where('role_id', $role->id)->where('department_id', $department->id)->delete();
        } else {
            DB::table('users_roles')->insert(['user_id' => $user->id, 'role_id' => $role->id, 'department_id' => request()->get('department_id')]);
        }

        return response()->json(['success' => true]);
    }

}