<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePackagesAndHotelsAddSlug extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('packages', function(Blueprint $table)
		{
            $table->string('slug');
		});

        Schema::table('hotels', function(Blueprint $table)
        {
            $table->string('slug');
        });
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{

        Schema::table('packages', function(Blueprint $table)
        {
            $table->dropColumn('slug');
        });

        Schema::table('hotels', function(Blueprint $table)
        {
            $table->dropColumn('slug');
        });


	}

}
