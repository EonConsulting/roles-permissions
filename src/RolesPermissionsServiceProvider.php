<?php
/**
 * Created by PhpStorm.
 * User: jharing10
 * Date: 2017/02/11
 * Time: 4:06 PM
 */

namespace EONConsulting\RolesPermissions;


use EONConsulting\RolesPermissions\Models\Permission;
use Gate;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class RolesPermissionsServiceProvider extends ServiceProvider {

    public function boot() {
        $this->publishMigrations();
        $this->routes();
        $this->views();
        $this->publishes([
            __DIR__.'/assets' => public_path('vendor/roles'),
        ], 'public');

        Permission::get()->map(function ($permission) {
            Gate::define($permission->name, function ($user) use ($permission) {
                return $user->hasPermissionTo($user->last_department_id, $permission) || $user->hasPermissionTo($user->last_department_id, 'Super Admin');
            });
        });

        Blade::directive('role', function ($role) {
            return "<?php if (auth()->check() && auth()->user()->hasRole({$role})): ?>";
        });

        Blade::directive('endrole', function ($role) {
            return "<?php endif; ?>";
        });

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

    public function views() {
        $this->loadViewsFrom(__DIR__.'/resources/views', 'eon.roles');
    }

    public function routes() {
        $this->loadRoutesFrom(__DIR__.'/Routes/web.php');
    }

}