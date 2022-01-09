<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSapCooisTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sap_coois', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('po');
			$table->string('fg');
			$table->string('activity');
			$table->string('wc');
			$table->string('list')->nullable();
			$table->string('material');
			$table->string('uom')->nullable();
			$table->string('description')->nullable();
			$table->integer('standard_qty')->nullable();
			$table->string('uom_desc')->nullable();
			$table->string('tpa')->nullable(); //after


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
		Schema::drop('sap_coois');
	}

}
