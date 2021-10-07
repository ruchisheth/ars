<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFieldrepsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('fieldreps', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id');
			$table->string('first_name', 64)->nullable();
			$table->string('last_name', 64)->nullable();
			$table->boolean('approved_for_work')->nullable()->default(0);
			$table->boolean('classification')->nullable()->default(0);
			$table->string('payment_terms', 16);
			$table->string('initial_status', 16);
			$table->boolean('paperwork_received')->default(0);
			$table->text('organization_name', 65535);
			$table->string('social_security', 32);
			$table->string('adp_file_no', 64);
			$table->date('dob')->nullable();
			$table->string('gender', 16);
			$table->string('highest_edu');
			$table->string('internet_browser');
			$table->string('distance_willing_to_travel');
			$table->boolean('is_employed');
			$table->string('occupation', 64)->nullable();
			$table->boolean('as_merchandiser')->nullable();
			$table->string('merchandiser_exp')->nullable();
			$table->boolean('can_print')->nullable();
			$table->boolean('has_camera')->nullable();
			$table->boolean('has_computer')->nullable();
			$table->boolean('has_smartphone')->nullable();
			$table->boolean('has_internet')->nullable();
			$table->text('experience', 65535)->nullable();
			$table->text('notes', 65535)->nullable();
			$table->string('have_done')->nullable();
			$table->string('interested_in')->nullable();
			$table->string('availability_monday', 16)->default('0,0,0');
			$table->string('availability_tuesday', 16)->default('0,0,0');
			$table->string('availability_wednesday', 16)->default('0,0,0');
			$table->string('availability_thursday', 16)->default('0,0,0');
			$table->string('availability_friday', 16)->default('0,0,0');
			$table->string('availability_saturday', 16)->default('0,0,0');
			$table->string('availability_sunday', 16)->default('0,0,0');
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
		Schema::drop('fieldreps');
	}

}
