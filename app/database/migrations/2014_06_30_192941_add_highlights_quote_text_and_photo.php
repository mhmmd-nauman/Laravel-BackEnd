<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddHighlightsQuoteTextAndPhoto extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('hotel_highlights', function(Blueprint $table)
		{
			$table->string('quote_text');
            $table->string('quote_author');
            $table->integer('default_quote_photo_id');
		});

        Schema::table('photos', function(Blueprint $table)
        {
            DB::unprepared("ALTER TABLE  `photos` CHANGE  `content_type`  `content_type` ENUM( 'hotels','packages','rooms','services','restaurants','activities','highlights', 'awards', 'highlights_quote' );");
        });
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('hotel_highlights', function(Blueprint $table)
		{
            $table->dropColumn('quote_text');
            $table->dropColumn('quote_author');
            $table->dropColumn('default_quote_photo_id');
		});

        Schema::table('photos', function(Blueprint $table)
        {
            DB::unprepared("ALTER TABLE  `photos` CHANGE  `content_type`  `content_type` ENUM( 'hotels','packages','rooms','services','restaurants','activities','highlights', 'awards' );");
        });
	}

}
