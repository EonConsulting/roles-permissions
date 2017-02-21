<?php
/**
 * Created by PhpStorm.
 * User: jharing10
 * Date: 2017/02/20
 * Time: 5:31 PM
 */

Route::group(['middleware' => ['web', 'auth'], 'prefix' => '/admin', 'namespace' => 'EONConsulting\\RolesPermissions\\Http\\Controllers\\'], function() {
    Route::group(['namespace' => 'Admin\\Roles\\'], function() {
        Route::get('/departments', ['as' => 'eon.admin.departments', 'uses' => 'DepartmentsController@index']);
        Route::get('/departments/create', ['as' => 'eon.admin.departments.create', 'uses' => 'DepartmentsController@create']);
        Route::post('/departments/create', ['as' => 'eon.admin.departments.create', 'uses' => 'DepartmentsController@store']);
        Route::get('/departments/{department}', ['as' => 'eon.admin.departments.single', 'uses' => 'DepartmentsController@show']);
        Route::post('/departments/{department}', ['as' => 'eon.admin.departments.single', 'uses' => 'DepartmentsController@update']);
        Route::post('/departments/{department}/delete', ['as' => 'eon.admin.departments.delete', 'uses' => 'DepartmentsController@destroy']);
        Route::post('/departments/--department--/delete', ['as' => 'eon.admin.departments.delete', 'uses' => 'DepartmentsController@destroy']);
        Route::get('/roles', ['as' => 'eon.admin.roles', 'uses' => 'RolesController@index']);
        Route::get('/roles/{role}', ['as' => 'eon.admin.roles.single', 'uses' => 'RolesController@show']);
        Route::post('/roles/{role}', ['as' => 'eon.admin.roles.single', 'uses' => 'RolesController@update_role']);
        Route::post('/roles/{role?}/{permission?}', ['as' => 'eon.admin.roles.permission', 'uses' => 'RolesController@update']);
        Route::post('/roles/--role--/--permission--', ['as' => 'eon.admin.roles.permission', 'uses' => 'RolesController@update']);
        Route::get('/permissions', ['as' => 'eon.admin.permissions', 'uses' => 'PermissionsController@index']);
        Route::get('/permissions/{permission}', ['as' => 'eon.admin.permissions.single', 'uses' => 'PermissionsController@show']);
        Route::post('/permissions/{permission}', ['as' => 'eon.admin.permissions.single', 'uses' => 'PermissionsController@update']);
        Route::get('/users', ['as' => 'eon.admin.roles.users', 'uses' => 'UsersController@index']);
        Route::get('/users/{user}', ['as' => 'eon.admin.roles.users.single', 'uses' => 'UsersController@show']);
        Route::post('/users/{user}/{role}/{department}', ['as' => 'eon.admin.roles.users.role-priovided', 'uses' => 'UsersController@update']);
        Route::post('/users/--user--/--role--/--department--', ['as' => 'eon.admin.roles.users.role', 'uses' => 'UsersController@update']);
    });
});