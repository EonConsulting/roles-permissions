<?php
/**
 * Created by PhpStorm.
 * User: jharing10
 * Date: 2017/02/11
 * Time: 4:42 PM
 */

namespace EONConsulting\RolesPermissions\Traits;

use EONConsulting\RolesPermissions\Models\Role;
use EONConsulting\RolesPermissions\Models\Permission;


trait HasPermissionTrait {

    public function givePermissionTo($department_id, ...$permissions)
    {
        $permissions = $this->getAllPermissions(array_flatten($permissions));

        if ($permissions === null) {
            return $this;
        }

        $this->permissions()->where('department_id', $department_id)->saveMany($permissions);

        return $this;
    }

    public function withdrawPermissionTo($department_id, ...$permissions)
    {
        $permissions = $this->getAllPermissions(array_flatten($permissions));

        $this->permissions()->where('department_id', $department_id)->detach($permissions);

        return $this;
    }

    public function updatePermissions($department_id, ...$permissions)
    {
        $this->permissions()->where('department_id', $department_id)->detach();

        return $this->givePermissionTo($permissions);
    }

    public function hasRole($department_id, ...$roles)
    {
        foreach ($roles as $role) {
            if ($this->roles->with('department_id', $department_id)->contains('name', $role)) {
                return true;
            }
        }

        return false;
    }

    public function hasPermissionTo($department_id, $permission)
    {
        return $this->hasPermissionThroughRole($permission, $department_id) || $this->hasPermission($department_id, $permission);
    }

    protected function hasPermissionThroughRole($department_id, $permission)
    {
        foreach ($permission->roles as $role) {
            if ($this->roles->with('department_id', $department_id)->contains($role)) {
                return true;
            }
        }

        return false;
    }

    protected function hasPermission($department_id, $permission) {
        return (bool) $this->permissions->where('name', $permission->name)->where('department_id', $department_id)->count();
    }

    protected function getAllPermissions(array $permissions) {
        return Permission::whereIn('name', $permissions)->get();
    }

    public function roles() {
        return $this->belongsToMany(Role::class, 'users_roles');
    }

    public function permissions() {
        return $this->belongsToMany(Permission::class, 'users_permissions');
    }

}