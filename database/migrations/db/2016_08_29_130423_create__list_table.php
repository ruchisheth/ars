<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateListTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('_list', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('list_name', 48);
			$table->string('item_name', 256);
			$table->string('color', 48);
			$table->integer('list_order');
			$table->integer('status')->default(1);
			$table->timestamps();
		});

		//DB::unprepared(File::get(AppHelper::ASSETS.'_list.sql'));
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('_list');
	}

}
