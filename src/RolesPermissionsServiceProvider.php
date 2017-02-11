<?php
/**
 * Created by PhpStorm.
 * User: jharing10
 * Date: 2017/02/11
 * Time: 4:06 PM
 */

namespace EONConsulting\RolesPermissions;


use Illuminate\Support\ServiceProvider;

class RolesPermissionsServiceProvider extends ServiceProvider {

    public function boot() {
        $this->publishMigrations();
    }

    public function register() {
        $this->app->singleton( 'roles_permissions', function () {
            return new RolesPermissions;
        });
    }

    private function publishMigrations() {
        $path = $this->getMigrationsPath();
        $this->publishes([$path => database_path('migrations')], 'migrations');
    }

    private function getMigrationsPath() {
        return __DIR__ . '/database/migrations/';
    }

}