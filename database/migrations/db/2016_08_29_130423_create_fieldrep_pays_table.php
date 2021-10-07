<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFieldrepPaysTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('fieldrep_pays', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('fieldrep_id');
			$table->string('project_type', 32);
			$table->string('client_id', 32);
			$table->string('item', 32);
			$table->string('rate', 6);
			$table->string('pay_type', 16);
			$table->text('notes', 65535);
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
		Schema::drop('fieldrep_pays');
	}

}
