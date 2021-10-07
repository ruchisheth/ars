<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAssignmentsInstructionsTableBatch2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('assignments_instructions', function($table)
        {
            $table->dropColumn(['id','round_id', 'instruction_name', 'is_default', 'instruction', 'attachment', 'offer_instruction', 'offer_attachment']);
            $table->integer('assignment_id')->nullable()->first();
            $table->integer('instruction_id')->nullable()->first();
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
