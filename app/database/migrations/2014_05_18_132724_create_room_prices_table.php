<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRoomPricesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('room_prices', function(Blueprint $table) {
			$table->increments('id');
            $table->unsignedInteger('room_id');
            $table->double('price', 10, 2);
            $table->enum('weekday', array('mon','tue','wed','thu','fri','sat','sun'));
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('room_prices');
	}

}
