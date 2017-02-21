<?php
/**
 * Created by PhpStorm.
 * User: jharing10
 * Date: 2017/02/20
 * Time: 5:31 PM
 */

Route::group(['middleware' => ['web', 'auth'], 'prefix' => '/admin', 'namespace' => 'EONConsulting\\RolesPermissions\\Http\\Controllers\\'], function() {
    Route::group(['namespace' => 'Admin\\Roles\\'], function() {
        Route::get('/roles', ['as' => 'eon.admin.roles', 'uses' => 'RolesController@index']);
        Route::get('/roles/{role}', ['as' => 'eon.admin.roles.single', 'uses' => 'RolesController@show']);
        Route::post('/roles/{role}', ['as' => 'eon.admin.roles.single', 'uses' => 'RolesController@update_role']);
        Route::post('/roles/{role?}/{permission?}', ['as' => 'eon.admin.roles.permission', 'uses' => 'RolesController@update']);
        Route::post('/roles/--role--/--permission--', ['as' => 'eon.admin.roles.permission', 'uses' => 'RolesController@update']);
        Route::get('/permissions', ['as' => 'eon.admin.permissions', 'uses' => 'PermissionsController@index']);
        Route::get('/permissions/{permission}', ['as' => 'eon.admin.permissions.single', 'uses' => 'PermissionsController@show']);
        Route::post('/permissions/{permission}', ['as' => 'eon.admin.permissions.single', 'uses' => 'PermissionsController@update']);
        Route::get('/users', ['as' => 'eon.admin.roles.users', 'uses' => 'UsersController@index']);
    });
});