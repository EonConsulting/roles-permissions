<?php
/**
 * Created by PhpStorm.
 * User: jharing10
 * Date: 2017/02/20
 * Time: 2:21 PM
 */

namespace EONConsulting\RolesPermissions\Models;


use Illuminate\Database\Eloquent\Model;

class Department extends Model {

    protected $table = 'departments';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'slug'];

}