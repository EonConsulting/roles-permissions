<?php
/**
 * Created by PhpStorm.
 * User: jharing10
 * Date: 2017/02/11
 * Time: 4:05 PM
 */

namespace EONConsulting\RolesPermissions;


use EONConsulting\RolesPermissions\Models\Permission;
use EONConsulting\RolesPermissions\Models\Role;

class RolesPermissions {

    public function create_role($role_str) {

        $role = Role::firstOrNew([
            'name' => $role_str,
            'slug' => str_slug($role_str)
        ]);
        $role->save();

        return $role;
    }

    public function create_permission(Role $role, $permission_str) {

        $permission = Permission::firstOrNew([
            'name' => $permission_str,
            'slug' => str_slug($permission_str)
        ]);
        $permission->save();

        if(!$role->hasPermission($permission)) {
            $role->permissions()->save($permission);
        }

        return $permission;
    }

}