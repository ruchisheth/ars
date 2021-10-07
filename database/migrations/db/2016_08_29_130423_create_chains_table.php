<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateChainsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('chains', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('client_id')->index('client_id');
			$table->integer('type')->nullable()->default(2);
			$table->text('chain_name', 65535);
			$table->text('chain_abbrev', 65535);
			$table->text('notes', 65535)->nullable();
			$table->text('retailer_type', 65535)->nullable();
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
		Schema::drop('chains');
	}

}
