<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstructionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instructions', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('round_id');
            $table->text('instruction_name', 65535);
            $table->boolean('is_default')->default(0);
            $table->text('instruction', 65535);
            $table->string('attachment', 1024);
            $table->text('offer_instruction', 65535);
            $table->string('offer_attachment', 1024);
            $table->timestamps();
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
