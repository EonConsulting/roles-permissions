<?php
/**
 * Created by PhpStorm.
 * User: jharing10
 * Date: 2017/02/20
 * Time: 2:21 PM
 */

namespace EONConsulting\RolesPermissions\Models;


use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Department extends Model {

    protected $table = 'departments';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'slug'];

    public function users_roles() {
        return $this->belongsToMany(User::class, 'users_roles')->withPivot('role_id');
    }

    public function users_permissions() {
        return $this->belongsToMany(Permission::class, 'users_permissions')->withPivot('permission_id');
    }

}