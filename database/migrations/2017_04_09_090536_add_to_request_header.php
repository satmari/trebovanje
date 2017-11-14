<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddToRequestHeader extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::table('request_header', function($table)
		{
    		// $table->string('flash')->nullable(); // added latter
    		// $table->string('postatus')->nullable(); // added latter
    		// $table->dateTime('lastmodified')->nullable(); // added latter
    		
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
