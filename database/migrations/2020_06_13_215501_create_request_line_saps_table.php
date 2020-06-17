<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestLineSapsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('request_line_sap', function(Blueprint $table)
		{
			$table->increments('id');

			$table->integer('request_header_id');

			$table->string('material');
			$table->string('uom')->nullable();
			$table->string('description')->nullable();
			$table->integer('standard_qty')->nullable(); 
    		$table->string('uom_desc')->nullable(); 

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
		Schema::drop('request_line_sap');
	}

}
