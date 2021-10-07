<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRoundsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('rounds', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('project_id');
			$table->integer('template_id')->nullable();
			$table->string('round_name');
			$table->date('start_date')->nullable();
			$table->date('deadline_date')->nullable();
			$table->date('reporting_date')->nullable();
			$table->time('start_time')->nullable();
			$table->time('estimated_duration')->nullable();
			$table->boolean('is_paperwork');
			$table->integer('survey_entry_before');
			$table->integer('survey_entry_after');
			$table->integer('visit_date_before');
			$table->integer('visit_date_after');
			$table->integer('email_reminder');
			$table->boolean('status')->default(1);
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
		Schema::drop('rounds');
	}

}
