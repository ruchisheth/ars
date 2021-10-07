<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAssignmentsOffersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('assignments_offers', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('assignment_id');
			$table->integer('fieldrep_id');
			$table->boolean('is_accepted')->nullable();
			$table->string('reason');
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
		Schema::drop('assignments_offers');
	}

}
