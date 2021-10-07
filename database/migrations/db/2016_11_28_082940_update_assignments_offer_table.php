<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAssignmentsOfferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('assignments_offers', function(Blueprint $table) {
        $table->integer('fieldrep_id')->unsigned()->change();
        $table->integer('assignment_id')->unsigned()->change();
        $table->foreign('fieldrep_id')->references('id')->on('fieldreps');
        $table->foreign('assignment_id')->references('id')->on('assignments');
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
