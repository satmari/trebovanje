<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('trans_items', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('item')->unique(); // added to more than 8
			$table->string('item_t',50)->nullable();

			$table->integer('std_qty')->nullable(); // added latter
    		$table->string('std_uom')->nullable(); // added latter

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
		Schema::drop('trans_items');
	}

}
