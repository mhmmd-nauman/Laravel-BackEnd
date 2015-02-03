<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAddressesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('addresses', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('country_id')->nullable();
            $table->unsignedInteger('state_id')->nullable();
            $table->unsignedInteger('city_id')->nullable();
            $table->unsignedInteger('neighbourhood_id')->nullable();
            $table->unsignedInteger('route_id')->nullable();
            $table->unsignedInteger('street_id')->nullable();
            $table->unsignedInteger('postal_code_id')->nullable();
            $table->double('lat', 12, 8);
            $table->double('lng', 12, 8);
            $table->string('address', 512);
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
		Schema::drop('addresses');
	}

}
