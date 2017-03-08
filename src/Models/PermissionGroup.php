<?php
/**
 * Created by PhpStorm.
 * User: jharing10
 * Date: 2017/02/20
 * Time: 3:59 PM
 */

namespace EONConsulting\RolesPermissions\Models;


use Illuminate\Database\Eloquent\Model;

class PermissionGroup extends Model {

    protected $table = 'permission_groups';
    protected $primaryKey = 'id';
    protected $fillable = ['group_name'];

    public function permissions() {
        return $this->belongsToMany(Permission::class, 'permission_group_items');
    }

}