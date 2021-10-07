<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAssignmentsPaymentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('assignments_payments', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('assignment_id');
			$table->integer('payment_type')->nullable()->default(121);
			$table->string('rep_payment_type', 16);
			$table->integer('qty')->nullable();
			$table->float('pay_rate')->default(0);
			$table->string('pay_type', 2)->default('h');
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
		Schema::drop('assignments_payments');
	}

}
