<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTempPrintsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('temp_prints', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('name');

			$table->string('so')->nullable();
			$table->string('po');
			$table->string('flash')->nullable();
			$table->string('first_time')->nullable();
			$table->string('printer')->nullable();

			$table->string('stylefg');
			$table->string('colorfg');
			$table->string('sizefg');

			$table->string('module')->nullable();
            $table->string('leader')->nullable();
            $table->string('comment')->nullable();

			$table->string('item_0')->nullable();
			$table->string('item_t_0')->nullable();
			$table->string('size_0')->nullable();
			$table->string('size_t_0')->nullable();
			$table->string('color_0')->nullable();
			$table->string('color_t_0')->nullable();
			$table->string('uom_0')->nullable();
			$table->string('hu_0')->nullable();
			$table->string('std_qty_0')->nullable();
			$table->string('std_uom_0')->nullable();

			$table->string('item_1')->nullable();
			$table->string('item_t_1')->nullable();
			$table->string('size_1')->nullable();
			$table->string('size_t_1')->nullable();
			$table->string('color_1')->nullable();
			$table->string('color_t_1')->nullable();
			$table->string('uom_1')->nullable();
			$table->string('hu_1')->nullable();
			$table->string('std_qty_1')->nullable();
			$table->string('std_uom_1')->nullable();

			$table->string('item_2')->nullable();
			$table->string('item_t_2')->nullable();
			$table->string('size_2')->nullable();
			$table->string('size_t_2')->nullable();
			$table->string('color_2')->nullable();
			$table->string('color_t_2')->nullable();
			$table->string('uom_2')->nullable();
			$table->string('hu_2')->nullable();
			$table->string('std_qty_2')->nullable();
			$table->string('std_uom_2')->nullable();

			$table->string('item_3')->nullable();
			$table->string('item_t_3')->nullable();
			$table->string('size_3')->nullable();
			$table->string('size_t_3')->nullable();
			$table->string('color_3')->nullable();
			$table->string('color_t_3')->nullable();
			$table->string('uom_3')->nullable();
			$table->string('hu_3')->nullable();
			$table->string('std_qty_3')->nullable();
			$table->string('std_uom_3')->nullable();

			$table->string('item_4')->nullable();
			$table->string('item_t_4')->nullable();
			$table->string('size_4')->nullable();
			$table->string('size_t_4')->nullable();
			$table->string('color_4')->nullable();
			$table->string('color_t_4')->nullable();
			$table->string('uom_4')->nullable();
			$table->string('hu_4')->nullable();
			$table->string('std_qty_4')->nullable();
			$table->string('std_uom_4')->nullable();

			$table->string('item_5')->nullable();
			$table->string('item_t_5')->nullable();
			$table->string('size_5')->nullable();
			$table->string('size_t_5')->nullable();
			$table->string('color_5')->nullable();
			$table->string('color_t_5')->nullable();
			$table->string('uom_5')->nullable();
			$table->string('hu_5')->nullable();
			$table->string('std_qty_5')->nullable();
			$table->string('std_uom_5')->nullable();

			$table->string('item_6')->nullable();
			$table->string('item_t_6')->nullable();
			$table->string('size_6')->nullable();
			$table->string('size_t_6')->nullable();
			$table->string('color_6')->nullable();
			$table->string('color_t_6')->nullable();
			$table->string('uom_6')->nullable();
			$table->string('hu_6')->nullable();
			$table->string('std_qty_6')->nullable();
			$table->string('std_uom_6')->nullable();

			$table->string('item_7')->nullable();
			$table->string('item_t_7')->nullable();
			$table->string('size_7')->nullable();
			$table->string('size_t_7')->nullable();
			$table->string('color_7')->nullable();
			$table->string('color_t_7')->nullable();
			$table->string('uom_7')->nullable();
			$table->string('hu_7')->nullable();
			$table->string('std_qty_7')->nullable();
			$table->string('std_uom_7')->nullable();

			$table->string('item_8')->nullable();
			$table->string('item_t_8')->nullable();
			$table->string('size_8')->nullable();
			$table->string('size_t_8')->nullable();
			$table->string('color_8')->nullable();
			$table->string('color_t_8')->nullable();
			$table->string('uom_8')->nullable();
			$table->string('hu_8')->nullable();
			$table->string('std_qty_8')->nullable();
			$table->string('std_uom_8')->nullable();

			$table->string('item_9')->nullable();
			$table->string('item_t_9')->nullable();
			$table->string('size_9')->nullable();
			$table->string('size_t_9')->nullable();
			$table->string('color_9')->nullable();
			$table->string('color_t_9')->nullable();
			$table->string('uom_9')->nullable();
			$table->string('hu_9')->nullable();
			$table->string('std_qty_9')->nullable();
			$table->string('std_uom_9')->nullable();

			$table->string('item_10')->nullable();
			$table->string('item_t_10')->nullable();
			$table->string('size_10')->nullable();
			$table->string('size_t_10')->nullable();
			$table->string('color_10')->nullable();
			$table->string('color_t_10')->nullable();
			$table->string('uom_10')->nullable();
			$table->string('hu_10')->nullable();
			$table->string('std_qty_10')->nullable();
			$table->string('std_uom_10')->nullable();

			$table->string('item_11')->nullable();
			$table->string('item_t_11')->nullable();
			$table->string('size_11')->nullable();
			$table->string('size_t_11')->nullable();
			$table->string('color_11')->nullable();
			$table->string('color_t_11')->nullable();
			$table->string('uom_11')->nullable();
			$table->string('hu_11')->nullable();
			$table->string('std_qty_11')->nullable();
			$table->string('std_uom_11')->nullable();

			$table->string('item_12')->nullable();
			$table->string('item_t_12')->nullable();
			$table->string('size_12')->nullable();
			$table->string('size_t_12')->nullable();
			$table->string('color_12')->nullable();
			$table->string('color_t_12')->nullable();
			$table->string('uom_12')->nullable();
			$table->string('hu_12')->nullable();
			$table->string('std_qty_12')->nullable();
			$table->string('std_uom_12')->nullable();

			$table->string('item_13')->nullable();
			$table->string('item_t_13')->nullable();
			$table->string('size_13')->nullable();
			$table->string('size_t_13')->nullable();
			$table->string('color_13')->nullable();
			$table->string('color_t_13')->nullable();
			$table->string('uom_13')->nullable();
			$table->string('hu_13')->nullable();
			$table->string('std_qty_13')->nullable();
			$table->string('std_uom_13')->nullable();

			$table->string('item_14')->nullable();
			$table->string('item_t_14')->nullable();
			$table->string('size_14')->nullable();
			$table->string('size_t_14')->nullable();
			$table->string('color_14')->nullable();
			$table->string('color_t_14')->nullable();
			$table->string('uom_14')->nullable();
			$table->string('hu_14')->nullable();
			$table->string('std_qty_14')->nullable();
			$table->string('std_uom_14')->nullable();


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
		Schema::drop('temp_prints');
	}

}
