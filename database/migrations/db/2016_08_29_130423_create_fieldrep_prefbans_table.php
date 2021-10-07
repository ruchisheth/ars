<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFieldrepPrefbansTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('fieldrep_prefbans', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('chain_id');
			$table->integer('site_id');
			$table->integer('fieldrep_id');
			$table->text('activity', 65535)->nullable();
			$table->boolean('pref_ban')->nullable();
			$table->boolean('status');
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
		Schema::drop('fieldrep_prefbans');
	}

}
