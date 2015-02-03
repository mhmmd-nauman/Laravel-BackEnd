<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLocationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('locations', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('location_id')->nullable();
            $table->enum('type', array('country','state','city','neighbourhood','route','street','postal_code'));
            $table->double('lat', 12, 8);
            $table->double('lng', 12, 8);
            $table->string('name');
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
		Schema::drop('locations');
	}

}
