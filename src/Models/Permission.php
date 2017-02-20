<?php
/**
 * Created by PhpStorm.
 * User: jharing10
 * Date: 2017/02/11
 * Time: 4:40 PM
 */

namespace EONConsulting\RolesPermissions\Models;


use Illuminate\Database\Eloquent\Model;

class Permission extends Model {

    protected $table = 'permissions';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'slug'];

    public function roles() {
        return $this->belongsToMany(Role::class, 'roles_permissions');
    }

}