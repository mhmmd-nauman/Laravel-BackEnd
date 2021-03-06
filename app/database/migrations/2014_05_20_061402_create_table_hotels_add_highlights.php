<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTableHotelsAddHighlights extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('hotel_highlights', function(Blueprint $table)
		{
            $table->increments('id');
            $table->string('name');
            $table->text('description');
            $table->tinyInteger('status');
            $table->integer('default_photo_id');
            $table->integer('hotel_id');
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
        Schema::drop('hotel_highlights');
	}

}
