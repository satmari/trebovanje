<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestHeaderSapsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('request_header_sap', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name')->unique();

			$table->string('so')->nullable();
			$table->string('po');

			$table->string('style');
			
			$table->string('module');
            $table->string('leader');

            $table->string('status');

            // $table->string('wmsstatus')->nullable(); // Nema u SAP-u
            // $table->string('color')->nullable(); // Nema u SAP-u
			// $table->string('size')->nullable();	 // Nema u SAP-u

			$table->string('activity')->nullable(); // Samo u SAP-u
            $table->string('wc')->nullable(); 		// Samo u SAP-u
			$table->string('list')->nullable();	 	// Samo u SAP-u
            
            $table->string('first_time');
            $table->smallInteger('deleted')->default(0);
            // $table->smallInteger('printed')->default(1);

            $table->string('comment')->nullable();

			$table->string('flash')->nullable(); // added latter
			$table->string('postatus')->nullable(); // added latter
			$table->dateTime('lastmodified')->nullable(); // added latter



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
		Schema::drop('request_header_saps');
	}

}
