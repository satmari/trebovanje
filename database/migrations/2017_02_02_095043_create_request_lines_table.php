<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestLinesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('request_line', function(Blueprint $table)
		{
			$table->increments('id');

			$table->integer('request_header_id');

			$table->string('item'); // added to more than 8 
			$table->string('item_t',32);

			$table->string('size',8);
			$table->string('size_t',32);

			$table->string('color',8);
			$table->string('color_t',32);

			$table->string('hu',30);
			$table->string('uom',8);

			$table->integer('std_qty')->nullable(); // added latter
    		$table->string('std_uom',10)->nullable(); // added latter

			$table->smallInteger('deleted')->default(0);

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
		Schema::drop('request_line');
	}

}

