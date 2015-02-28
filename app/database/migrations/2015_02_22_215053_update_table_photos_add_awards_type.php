<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateTablePhotosAddAwardsType extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('photos', function(Blueprint $table)
		{
            DB::unprepared("ALTER TABLE  `photos` CHANGE  `content_type`  `content_type` ENUM( 'hotels','packages','rooms','services','restaurants','activities','highlights', 'awards' );");
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('photos', function(Blueprint $table)
		{
            DB::unprepared("ALTER TABLE  `photos` CHANGE  `content_type`  `content_type` ENUM( 'hotels','packages','rooms','services','restaurants','activities','highlights' );");
		});
	}

}
