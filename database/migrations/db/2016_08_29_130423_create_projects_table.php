<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProjectsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('projects', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('chain_id')->index('chain_id');
			$table->string('project_name');
			$table->text('project_type', 65535);
			$table->integer('job_number');
			$table->text('division', 65535);
			$table->boolean('can_schedule')->default(0);
			$table->string('primary_contact');
			$table->string('secondary_contact');
			$table->text('billing_contact', 65535);
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
		Schema::drop('projects');
	}

}
