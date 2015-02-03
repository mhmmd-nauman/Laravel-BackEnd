<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDiscountsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('discounts', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('name');
            $table->string('code');
            $table->integer('count');
            $table->enum('price_type', array('person', 'booking'));
            $table->dateTime('expire');
            $table->integer('discount');
			$table->timestamps();
            $table->unique('code');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('discounts');
	}

}
