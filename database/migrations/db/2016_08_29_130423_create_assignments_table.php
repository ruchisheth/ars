<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAssignmentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('assignments', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('round_id');
			$table->integer('site_id');
			$table->integer('instruction_id')->nullable();
			$table->integer('fieldrep_id')->nullable();
			$table->integer('assignment_code');
			$table->date('schedule_date')->nullable();
			$table->date('start_date')->nullable();
			$table->date('deadline_date')->nullable();
			$table->time('start_time')->nullable();
			$table->time('estimated_duration')->nullable();
			$table->date('actual_visit_date')->nullable();
			$table->boolean('is_offered')->default(0);
			$table->integer('status')->default(0);
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
		Schema::drop('assignments');
	}

}
