<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTableBatch2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('users')){
                Schema::create('users', function (Blueprint $table) {
                    $table->increments('id');
                    $table->string('email', 100)->unique();
                    $table->string('password',64);
                    $table->enum('user_type', array('S', 'A', 'C', 'F'))->nullable()->default(NULL)->index()->comment('S = Super Admin, F = Admin, C = Client, F = FieldRep');
                    $table->integer('role');
                    $table->integer('sub_role')->nullable()->default(NULL);
                    $table->string('client_code', 10);
                    $table->integer('db_version');
                    $table->tinyInteger('is_first')->default(false);
                    $table->tinyInteger('status');
                    $table->rememberToken();
                    $table->timestamps();
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
