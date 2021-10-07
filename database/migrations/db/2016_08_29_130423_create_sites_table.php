<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSitesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sites', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('site_code');
			$table->integer('chain_id')->index('chain_id');
			$table->integer('fieldrep_id')->nullable();
			$table->string('site_name')->nullable();
			$table->string('distribution_center');
			$table->string('open_date')->nullable();
			$table->string('street')->nullable();
			$table->string('city', 64)->nullable();
			$table->string('state', 64)->nullable();
			$table->text('zipcode', 65535)->nullable();
			$table->double('lat', 10, 6)->nullable();
			$table->double('long', 10, 6)->nullable();
			$table->string('phone_number', 32)->nullable();
			$table->string('fax_number', 32)->nullable();
			$table->text('notes', 65535)->nullable();
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
		Schema::drop('sites');
	}

}
