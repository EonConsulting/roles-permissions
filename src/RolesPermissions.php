<?php
/**
 * Created by PhpStorm.
 * User: jharing10
 * Date: 2017/02/11
 * Time: 4:05 PM
 */

namespace EONConsulting\RolesPermissions;


use EONConsulting\RolesPermissions\Models\Department;
use EONConsulting\RolesPermissions\Models\Permission;
use EONConsulting\RolesPermissions\Models\PermissionGroup;
use EONConsulting\RolesPermissions\Models\Role;
use Illuminate\Support\Facades\DB;

class RolesPermissions {

    public function create_department($dept_str) {
        $dept = Department::firstOrNew([
            'name' => $dept_str,
            'slug' => str_slug($dept_str)
        ]);
        $dept->save();

        return $dept;
    }

    public function remove_department(Department $dept) {
        if(!$dept)
            return false;

        if($dept->users_roles()->count() > 0 || $dept->users_permissions()->count() > 0)
            return false;

        $dept->delete();

        return true;
    }

    public function create_role($role_str) {
        $role = Role::firstOrNew([
            'name' => $role_str,
            'slug' => str_slug($role_str)
        ]);
        $role->save();

        return $role;
    }

    public function remove_role(Role $role) {
        if(!$role)
            return false;

        if(count($role->users()) > 0)
            return false;

        $role->delete();

        return true;
    }

    public function create_permission(Role $role, ...$permission_str) {

        $permissions = [];
        foreach($permission_str as $str) {
            echo '<pre>';
            print_r(str_slug($str));
            echo '</pre>';
            $permission = Permission::firstOrNew([
                'name' => $str,
                'slug' => str_slug($str)
            ]);
            $permission->save();

            if(!$role->hasPermission($permission)) {
                $role->permissions()->save($permission);
            }

            $permissions[] = $permission;
        }

        if(count($permissions) > 1) {
            $group = new PermissionGroup;
            $group->group_name = '';
            $group->save();

            foreach($permissions as $permission) {
                DB::table('permission_group_items')->insert(['group_id' => $group->id, 'permission_id' => $permission->id]);
            }
        }

        return $permissions;
    }

    public function remove_permission_from_role(Role $role, $permission) {
        if($permission instanceof Permission) {
            if($role->hasPermission($permission)) {
                $role->permissions()->where('id', $permission->id)->detach();
                return true;
            }
        } else {
            $permission_obj = Permission::where('name', $permission)->orWhere('slug', $permission)->first();

            if($role->permissions->contains('name', $permission) || $role->permissions->contains('slug', $permission)) {
                $role->permissions()->detach($permission_obj->id);
                return true;
            }
        }

        return false;
    }

    public function remove_all_permissions_from_role(Role $role) {
        $role->permissions()->detach();
        return true;
    }

}