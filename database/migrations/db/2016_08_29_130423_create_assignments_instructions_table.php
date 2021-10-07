<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAssignmentsInstructionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('assignments_instructions', function(Blueprint $table)
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
		Schema::drop('assignments_instructions');
	}

}
