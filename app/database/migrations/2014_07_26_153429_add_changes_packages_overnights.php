<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddChangesPackagesOvernights extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('packages', function(Blueprint $table)
		{
			$table->renameColumn('overnights', 'overnights_min');
            $table->unsignedInteger('overnights_max')->default(0);
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
            $table->renameColumn('overnights_min', 'overnights');
            $table->removeColumn('overnights_max');
		});
	}

}
