<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTreatmentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('treatments', function(Blueprint $table) {
			$table->increments('id');
            $table->string('name');
            $table->text('description');
            $table->unsignedInteger('persons');
            $table->float('price', 8, 2);
            $table->unsignedInteger('duration');
            $table->unsignedInteger('default_photo')->nullable();
            $table->unsignedInteger('spa_id');
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
		Schema::drop('treatments');
	}

}
