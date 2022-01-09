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
		// Schema::table('trans_items', function (Blueprint $table) {
  //   		$table->string('item')->change();
		// });

		// Schema::table('request_line', function (Blueprint $table) {
  //   		$table->string('item')->change();
		// });

		// Schema::table('temp_print_saps', function (Blueprint $table) {
  //   		$table->string('module_pk')->nullable();
		// });

		// Schema::table('request_header_sap', function (Blueprint $table) {
  //   		$table->string('approval')->nullable();
		// });

		// Schema::table('temp_print_saps', function (Blueprint $table) {
  //   		$table->string('approval')->nullable();
		// });

		// Schema::table('sap_coois', function (Blueprint $table) {
  //   		$table->string('tpa')->nullable(); //after
		// });

		// Schema::table('request_line_sap', function (Blueprint $table) {
  //   		$table->string('tpa')->nullable(); // added
		// });

		Schema::table('temp_print_saps', function (Blueprint $table) {
    		// $table->string('tpa_0')->nullable(); // added
    		// $table->string('tpa_1')->nullable(); // added
    		// $table->string('tpa_2')->nullable(); // added
    		// $table->string('tpa_3')->nullable(); // added
    		// $table->string('tpa_4')->nullable(); // added
    		// $table->string('tpa_5')->nullable(); // added
    		// $table->string('tpa_6')->nullable(); // added
    		// $table->string('tpa_7')->nullable(); // added
    		// $table->string('tpa_8')->nullable(); // added
    		// $table->string('tpa_9')->nullable(); // added
    		// $table->string('tpa_10')->nullable(); // added
    		// $table->string('tpa_11')->nullable(); // added
    		// $table->string('tpa_12')->nullable(); // added
    		// $table->string('tpa_13')->nullable(); // added
    		// $table->string('tpa_14')->nullable(); // added
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
