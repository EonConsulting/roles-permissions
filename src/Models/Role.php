<?php
/**
 * Created by PhpStorm.
 * User: jharing10
 * Date: 2017/02/11
 * Time: 4:41 PM
 */

namespace EONConsulting\RolesPermissions\Models;


use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Role extends Model {

    protected $table = 'roles';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'slug'];

    public function permissions() {
        return $this->belongsToMany(Permission::class, 'roles_permissions');
    }

    public function users() {
        return $this->belongsToMany(User::class, 'users_roles')->withPivot('group_id');
    }

    public function hasPermission(Permission $permission) {
        return $this->permissions->contains('id', $permission->id);
    }

}