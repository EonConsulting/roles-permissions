<?php
/**
 * Created by PhpStorm.
 * User: jharing10
 * Date: 2017/02/20
 * Time: 5:49 PM
 */

namespace EONConsulting\RolesPermissions\Http\Controllers\Admin\Roles;


use App\Http\Controllers\Controller;
use EONConsulting\RolesPermissions\Models\Role;

class RolesController extends Controller {

    public function index() {
        $roles = Role::with('permissions')->with('users')->get();

        return view('eon.roles::roles', ['roles' => $roles]);
    }

}