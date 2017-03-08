<?php
/**
 * Created by PhpStorm.
 * User: jharing10
 * Date: 2017/02/11
 * Time: 4:42 PM
 */

namespace EONConsulting\RolesPermissions\Traits;

use EONConsulting\RolesPermissions\Models\Group;
use EONConsulting\RolesPermissions\Models\Role;
use EONConsulting\RolesPermissions\Models\Permission;
use Illuminate\Support\Facades\DB;


trait HasPermissionTrait {

    public function givePermissionTo($group_id, ...$permissions) {
        $permissions = $this->getAllPermissions(array_flatten($permissions));

        if ($permissions === null) {
            return $this;
        }

        foreach($permissions as $permission) {
            if($group_id instanceof Group) {
                $group_id = $group_id->id;
            }
            DB::table('users_permissions')->insert(['permission_id' => $permission->id, 'group_id' => $group_id, 'user_id' => $this->id]);
        }

        $this->permissions()->saveMany($permissions);

        return $this;
    }

    public function giveRole($group_id, ...$roles) {
        $roles = $this->getAllRoles(array_flatten($roles));

        if ($roles === null) {
            return $this;
        }

        foreach($roles as $role) {
            if($group_id instanceof Group) {
                $group_id = $group_id->id;
            }
            DB::table('users_roles')->insert(['role_id' => $role->id, 'group_id' => $group_id, 'user_id' => $this->id]);
        }

//        $this->roles()->saveMany($roles);

        return $this;
    }

    public function withdrawPermissionTo($group_id, ...$permissions) {
        $permissions = $this->getAllPermissions(array_flatten($permissions));

        $this->permissions()->where('group_id', $group_id)->detach($permissions);

        return $this;
    }

    public function updatePermissions($group_id, ...$permissions) {
        $this->permissions()->where('group_id', $group_id)->detach();

        return $this->givePermissionTo($group_id, $permissions);
    }

    public function hasRole($group_id = -1, ...$roles) {
        foreach ($roles as $role) {
            if($group_id != -1) {
                if ($this->roles->with('group_id', $group_id)->contains('name', $role)) {
                    return true;
                }
            } else {
                if ($this->roles->contains('name', $role)) {
                    return true;
                }
            }
        }

        return false;
    }

    public function hasPermissionTo($group_id, $permission) {
        return $this->hasPermissionThroughRole($permission, $group_id) || $this->hasPermission($group_id, $permission);
    }

    protected function hasPermissionThroughRole($group_id, $permission) {
        foreach ($permission->roles as $role) {
            if ($this->roles->with('group_id', $group_id)->contains($role)) {
                return true;
            }
        }

        return false;
    }

    protected function hasPermission($group_id, $permission) {
        return (bool) $this->permissions->where('name', $permission->name)->where('group_id', $group_id)->count();
    }

    protected function getAllPermissions(array $permissions) {
        return Permission::whereIn('name', $permissions)->orWhereIn('slug', $permissions)->get();
    }

    protected function getAllRoles(array $roles) {
        return Role::whereIn('name', $roles)->orWhereIn('slug', $roles)->get();
    }

    public function roles() {
        return $this->belongsToMany(Role::class, 'users_roles')->withPivot('group_id');
    }

    public function permissions() {
        return $this->belongsToMany(Permission::class, 'users_permissions')->withPivot('group_id');
    }

}