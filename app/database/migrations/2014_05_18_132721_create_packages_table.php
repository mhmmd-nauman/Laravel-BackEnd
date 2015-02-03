<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePackagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('packages', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->string('short_description', 1024);
            $table->text('description');
            $table->unsignedInteger('overnights')->nullable();
            $table->unsignedInteger('discount')->nullable();
            $table->unsignedInteger('default_room_id')->nullable();
            $table->unsignedInteger('last_minute')->nullable();
            $table->unsignedInteger('campaign')->nullable();
            $table->tinyInteger('recommended');
            $table->tinyInteger('status');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->unsignedInteger('days_in_advance')->nullable();
            $table->unsignedInteger('days_available')->nullable();
            $table->text('package_includes');
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
		Schema::drop('packages');
	}

}
