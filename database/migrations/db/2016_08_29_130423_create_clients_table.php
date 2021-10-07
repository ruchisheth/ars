<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateClientsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('clients', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('type')->default(1);
			$table->string('client_name');
			$table->string('client_abbrev', 32);
			$table->text('notes', 65535)->nullable();
			$table->string('client_logo')->nullable();
			$table->text('ship_via', 65535);
			$table->string('service_level', 128);
			$table->string('shipping_acc_number', 32);
			$table->boolean('status')->nullable()->default(1);
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
		Schema::drop('clients');
	}

}
