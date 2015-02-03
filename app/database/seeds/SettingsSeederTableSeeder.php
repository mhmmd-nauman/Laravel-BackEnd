<?php

class SettingsSeederTableSeeder extends Seeder {

	public function run()
	{

        Settings::create(array(
            'name' => 'order_email',
            'value' => ''
        ));

        Settings::create(array(
            'name' => 'google_analytics',
            'value' => ''
        ));

	}

}