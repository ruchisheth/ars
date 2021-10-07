<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAssignmentTableBatch7 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //actual_visit_date
        Schema::table('assignments', function(Blueprint $table) {
           $table->boolean('is_scheduled')->default(false)->after('actual_visit_date');
           $table->boolean('is_reported')->default(false)->after('is_offered');
           $table->boolean('is_partial')->default(false)->after('is_reported');
           $table->boolean('is_approved')->default(false)->after('is_partial');
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
