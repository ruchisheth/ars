<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAssignmentTableBatch6 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('assignments', function(Blueprint $table) {
           $table->integer('round_id')->unsigned()->change();
           $table->integer('site_id')->unsigned()->change();
           $table->integer('fieldrep_id')->unsigned()->change();
           
           $table->foreign('round_id')->references('id')->on('rounds');
           $table->foreign('site_id')->references('id')->on('sites');
           $table->foreign('fieldrep_id')->references('id')->on('fieldreps');
        });
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
