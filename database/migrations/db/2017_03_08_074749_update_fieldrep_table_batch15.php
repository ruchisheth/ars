<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateFieldrepTableBatch15 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fieldreps', function(Blueprint $table) {
            $table->integer('organization_name')->unsigned()->nullable()->default(null)->after('paperwork_received');
            $table->foreign('organization_name')->references('id')->on('fieldrep_orgs');
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
