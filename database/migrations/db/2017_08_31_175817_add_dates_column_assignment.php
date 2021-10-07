<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDatesColumnAssignment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('assignments', function($table) {
            $table->dateTime('scheduled_at')->nullable()->default(NULL)->after('is_approved');
            $table->dateTime('reported_at')->nullable()->default(NULL)->after('scheduled_at');
            $table->dateTime('partial_at')->nullable()->default(NULL)->after('reported_at');
            $table->dateTime('approved_at')->nullable()->default(NULL)->after('partial_at');
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
