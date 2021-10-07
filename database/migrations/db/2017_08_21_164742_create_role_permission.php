<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolePermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('role_permissions'))
        {
            // Schema::create('permission_roles_user', function (Blueprint $table) {
            Schema::create('role_permissions', function (Blueprint $table) {
                $table->increments('id_role_permission');
                $table->integer('id_role')->unsigned()->index();
                $table->integer('id_permission')->unsigned()->index();
                $table->timestamps();
                // $table->integer('roles_user_id');
                // $table->integer('permission_id');
                // $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
