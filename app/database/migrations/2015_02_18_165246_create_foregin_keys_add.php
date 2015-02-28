<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateForeginKeysAdd extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('locations', function($table) {
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('set null')->onUpdate('cascade');
        });

        Schema::table('addresses', function($table) {
            $table->foreign('country_id')->references('id')->on('locations')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('state_id')->references('id')->on('locations')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('city_id')->references('id')->on('locations')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('neighbourhood_id')->references('id')->on('locations')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('route_id')->references('id')->on('locations')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('street_id')->references('id')->on('locations')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('postal_code_id')->references('id')->on('locations')->onDelete('set null')->onUpdate('cascade');
        });

        Schema::table('packages', function($table) {
            $table->foreign('default_room_id')->references('id')->on('rooms')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('hotels', function($table) {
            $table->foreign('address_id')->references('id')->on('addresses')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('rooms', function($table) {
            $table->foreign('hotel_id')->references('id')->on('hotels')->onDelete('restrict')->onUpdate('cascade');
        });

        Schema::table('room_prices', function($table) {
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('restrict')->onUpdate('cascade');
        });

        Schema::table('package_rooms', function($table) {
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('spas', function($table) {
            $table->foreign('hotel_id')->references('id')->on('hotels')->onDelete('restrict')->onUpdate('cascade');
        });

        Schema::table('tags', function($table) {
            $table->foreign('tag_id')->references('id')->on('tag')->onDelete('restrict')->onUpdate('cascade');
        });

        Schema::table('tag', function($table) {
            $table->foreign('id')->references('tag_id')->on('tags')->onDelete('cascade')->onUpdate('cascade');
        });
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//Schema::drop('foregin_keys_add');
	}

}
