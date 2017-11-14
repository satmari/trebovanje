<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestHeadersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('request_header', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('name')->unique();

			$table->string('so', 10)->nullable();
			$table->string('po', 20);

			$table->string('style', 8);
			$table->string('color', 8);
			$table->string('size', 8);

			$table->string('module', 8);
            $table->string('leader', 40);

            $table->string('status', 20);
            $table->string('sowmsstatus', 10)->nullable();
            
            $table->string('first_time', 10);
            $table->smallInteger('deleted')->default(0);
            // $table->smallInteger('printed')->default(1);

            $table->string('comment', 50)->nullable();

			$table->timestamps();

			$table->string('flash')->nullable(); // added latter
			$table->string('postatus')->nullable(); // added latter
			$table->dateTime('lastmodified')->nullable(); // added latter
			
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('request_header');
	}

}
