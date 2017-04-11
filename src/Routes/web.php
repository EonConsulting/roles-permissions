<?php
/**
 * Created by PhpStorm.
 * User: jharing10
 * Date: 2017/02/20
 * Time: 5:31 PM
 */

Route::group(['middleware' => ['web', 'auth', 'instructor'], 'prefix' => '/admin', 'namespace' => 'EONConsulting\\RolesPermissions\\Http\\Controllers\\'], function() {
    Route::group(['namespace' => 'Admin\\Roles\\'], function() {
        Route::get('/groups', ['as' => 'eon.admin.groups', 'uses' => 'GroupsController@index']);
        Route::get('/groups/create', ['as' => 'eon.admin.groups.create', 'uses' => 'GroupsController@create']);
        Route::post('/groups/create', ['as' => 'eon.admin.groups.create', 'uses' => 'GroupsController@store']);
        Route::get('/groups/{group}', ['as' => 'eon.admin.groups.single', 'uses' => 'GroupsController@show']);
        Route::post('/groups/{group}', ['as' => 'eon.admin.groups.single', 'uses' => 'GroupsController@update']);
        Route::post('/groups/{group}/delete', ['as' => 'eon.admin.groups.delete', 'uses' => 'GroupsController@destroy']);
        Route::post('/groups/--group--/delete', ['as' => 'eon.admin.groups.delete', 'uses' => 'GroupsController@destroy']);

        Route::get('/roles', ['as' => 'eon.admin.roles', 'uses' => 'RolesController@index']);
        Route::get('/roles/create', ['as' => 'eon.admin.roles.create', 'uses' => 'RolesController@create']);
        Route::post('/roles/create', ['as' => 'eon.admin.roles.create', 'uses' => 'RolesController@store']);
        Route::get('/roles/{role}', ['as' => 'eon.admin.roles.single', 'uses' => 'RolesController@show']);
        Route::post('/roles/{role}', ['as' => 'eon.admin.roles.single', 'uses' => 'RolesController@update_role']);
        Route::post('/roles/{role}/delete', ['as' => 'eon.admin.roles.delete', 'uses' => 'RolesController@destroy']);
        Route::post('/roles/--role--/delete', ['as' => 'eon.admin.roles.delete', 'uses' => 'RolesController@destroy']);
        Route::post('/roles/{role?}/{permission?}', ['as' => 'eon.admin.roles.permission', 'uses' => 'RolesController@update']);
        Route::post('/roles/--role--/--permission--', ['as' => 'eon.admin.roles.permission', 'uses' => 'RolesController@update']);

        Route::get('/permissions', ['as' => 'eon.admin.permissions', 'uses' => 'PermissionsController@index']);
        Route::get('/permissions/create', ['as' => 'eon.admin.permissions.create', 'uses' => 'PermissionsController@create']);
        Route::post('/permissions/create', ['as' => 'eon.admin.permissions.create', 'uses' => 'PermissionsController@store']);
        Route::get('/permissions/{permission}', ['as' => 'eon.admin.permissions.single', 'uses' => 'PermissionsController@show']);
        Route::post('/permissions/{permission}', ['as' => 'eon.admin.permissions.single', 'uses' => 'PermissionsController@update']);
        Route::post('/permissions/{permission}/delete', ['as' => 'eon.admin.permissions.delete', 'uses' => 'PermissionsController@destroy']);
        Route::post('/permissions/--permission--/delete', ['as' => 'eon.admin.permissions.delete', 'uses' => 'PermissionsController@destroy']);

        Route::get('/users', ['as' => 'eon.admin.roles.users', 'uses' => 'UsersController@index']);
        Route::get('/users/{user}', ['as' => 'eon.admin.roles.users.single', 'uses' => 'UsersController@show']);
        Route::post('/users/{user}/{role}/{department}', ['as' => 'eon.admin.roles.users.role-priovided', 'uses' => 'UsersController@update']);
        Route::post('/users/--user--/--role--/--department--', ['as' => 'eon.admin.roles.users.role', 'uses' => 'UsersController@update']);
    });
});