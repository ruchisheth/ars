<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFieldrepsCriteriaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('fieldreps_criteria', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('round_id');
			$table->boolean('approved_for_work')->nullable();
			$table->boolean('has_camera')->nullable();
			$table->boolean('has_internet')->nullable();
			$table->boolean('exp_match_project_type')->nullable();
			$table->string('gender', 16)->nullable();
			$table->string('distance', 4)->nullable();
			$table->string('allowable_days', 128)->nullable();
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
		Schema::drop('fieldreps_criteria');
	}

}
