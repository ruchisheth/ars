<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateContactsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('contacts', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('entity_type');
			$table->integer('reference_id')->index('reference_id_2');
			$table->text('contact_type', 65535);
			$table->text('contact_type_other', 65535);
			$table->text('first_name', 65535);
			$table->text('last_name', 65535);
			$table->text('initial', 65535);
			$table->text('organization', 65535);
			$table->text('title', 65535);
			$table->string('email');
			$table->string('phone_number', 16);
			$table->string('fax_number', 16);
			$table->string('cell_number', 16);
			$table->string('pager_number', 16);
			$table->string('address1')->nullable();
			$table->string('address2')->nullable();
			$table->string('city', 64);
			$table->text('state', 65535);
			$table->text('zipcode', 65535)->nullable();
			$table->boolean('billing_contact');
			$table->text('notes', 65535);
			$table->double('lat', 10, 6)->nullable();
			$table->double('long', 10, 6)->nullable();
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
		Schema::drop('contacts');
	}

}
