<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateHotelsTableAddSummary extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('hotels', function(Blueprint $table)
        {
            $table->string('summary', 1024);
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('hotels', function(Blueprint $table)
        {
            $table->dropColumn('summary');
        });
	}

}
