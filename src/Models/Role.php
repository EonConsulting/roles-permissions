<?php
/**
 * Created by PhpStorm.
 * User: jharing10
 * Date: 2017/02/11
 * Time: 4:41 PM
 */

namespace EONConsulting\RolesPermissions\Models;


use Illuminate\Database\Eloquent\Model;

class Role extends Model {

    public function permissions() {
        return $this->belongsToMany(Permission::class, 'roles_permissions');
    }

}