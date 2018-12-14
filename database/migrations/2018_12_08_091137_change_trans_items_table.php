<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTransItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		//
		Schema::table('trans_items', function (Blueprint $table) {
    		$table->string('item')->change();
		});

		Schema::table('request_line', function (Blueprint $table) {
    		$table->string('item')->change();
		});
		

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
