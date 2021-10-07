<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePrefbanTableBatch4 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fieldrep_prefbans', function(Blueprint $table) {
            $table->integer('fieldrep_id')->unsigned()->change();
            $table->foreign('fieldrep_id')->references('id')->on('fieldreps')->onDelete('cascade');
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
