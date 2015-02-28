<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class DeleteRoomsPriceFkAndAlterSettings extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('settings', function(Blueprint $table)
        {
            DB::unprepared("ALTER TABLE  `settings` CHANGE  `value`  `value` VARCHAR( 4096 );");
        });

        Schema::table('room_prices', function(Blueprint $table)
        {
            //$table->dropForeign('room_prices_room_id_foreign');
        });
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{

	}

}
