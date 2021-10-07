<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableAdminSubscription extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('admin_subscriptions'))
        {
            Schema::create('admin_subscriptions', function (Blueprint $table) {
                $table->increments('id_admin_subscription');
                $table->integer('id_admin')->unsigned();
                $table->dateTime('subscription_start_on');
                $table->dateTime('subscription_end_on')->nullable()->default(NULL);
                $table->tinyInteger('status')->default(1)->index();
                
                $table->timestamps();
                
                //Add foreign key constrain in id_campus column
                $table->foreign('id_admin')
                        ->references('id_admin')->on('admins')
                        ->onDelete('cascade');
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
