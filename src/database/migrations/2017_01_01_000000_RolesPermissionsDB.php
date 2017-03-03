<?php
use \Illuminate\Support\Facades\Schema;
use \Illuminate\Database\Schema\Blueprint;
use \Illuminate\Database\Migrations\Migration;
/**
 * Created by PhpStorm.
 * User: jharing10
 * Date: 2017/02/06
 * Time: 10:58 AM
 */
class RolesPermissionsDB extends Migration  {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('slug');
            $table->timestamps();
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->timestamps();
        });

        Schema::create('permission_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('group_name');
            $table->timestamps();
        });

        Schema::create('permission_group_items', function (Blueprint $table) {
            $table->increments('permission_group_id');
            $table->integer('group_id')->unsigned();
            $table->integer('permission_id')->unsigned();
            $table->timestamps();

            $table->foreign('group_id')->references('id')->on('permission_groups')->onDelete('cascade');
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
        });

        Schema::create('groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->timestamps();
        });

        Schema::create('users_permissions', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('permission_id')->unsigned();
            $table->integer('group_id')->unsigned()->nullable();
            $table->tinyInteger('has_permission')->default(1);

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');

            $table->primary(['user_id', 'permission_id', 'group_id']);
        });

        Schema::create('users_roles', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('role_id')->unsigned();
            $table->integer('group_id')->unsigned()->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');

            $table->primary(['user_id', 'role_id', 'group_id']);
        });

        Schema::create('roles_permissions', function (Blueprint $table) {
            $table->integer('role_id')->unsigned();
            $table->integer('permission_id')->unsigned();

            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');

            $table->primary(['role_id', 'permission_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('roles');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('groups');
        Schema::dropIfExists('users_permissions');
        Schema::dropIfExists('users_roles');
        Schema::dropIfExists('roles_permissions');
    }

}