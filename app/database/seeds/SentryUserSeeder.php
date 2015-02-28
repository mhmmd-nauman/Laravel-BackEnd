<?php

class SentryUserSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        DB::table('users')->delete();
//
        Sentry::getUserProvider()->create(array(
            'email'    => 'mhmmd.nauman@gmail.com',
            'password' => '123456',
            'activated' => 1,
        ));

        



    }

}