<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTablePackageTreatments extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('package_treatments', function(Blueprint $table)
		{
			$table->increments('id');
            $table->unsignedInteger('treatment_id');
            $table->unsignedInteger('package_id');
            $table->tinyInteger('status');
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
		Schema::drop('package_treatments');
	}

}
