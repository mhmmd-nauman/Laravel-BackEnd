<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTableLocationGroups extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('location_groups', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('name');
            $table->string('description', 1024);
            $table->string('slug');
            $table->enum('type', array('city', 'area'));
            $table->tinyInteger('status')->default(0);
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
		Schema::drop('location_groups');
	}

}
