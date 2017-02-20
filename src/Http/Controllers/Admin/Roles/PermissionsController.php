<?php
/**
 * Created by PhpStorm.
 * User: jharing10
 * Date: 2017/02/20
 * Time: 5:56 PM
 */

namespace EONConsulting\RolesPermissions\Http\Controllers\Admin\Roles;


use App\Http\Controllers\Controller;
use EONConsulting\RolesPermissions\Models\Permission;

class PermissionsController extends Controller {

    public function index() {
        $permissions = Permission::with('roles')->with('users')->get();

        return view('eon.roles::permissions', ['permissions' => $permissions]);
    }

}