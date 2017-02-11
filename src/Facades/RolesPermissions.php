<?php
/**
 * Created by PhpStorm.
 * User: jharing10
 * Date: 2017/02/11
 * Time: 7:09 PM
 */

namespace EONConsulting\RolesPermissions\Facades;


use Illuminate\Support\Facades\Facade;

class RolesPermissions extends Facade {

    protected static function getFacadeAccessor() {
        return 'roles_permissions';
    }

}