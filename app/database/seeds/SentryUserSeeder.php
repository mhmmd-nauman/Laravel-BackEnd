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
            'email'    => 'Caroline@spason.se',
            'password' => 'endofbadbooking',
            'activated' => 1,
        ));

        Sentry::getUserProvider()->create(array(
            'email'    => 'Magnus@spason.se',
            'password' => 'endofbadbooking',
            'activated' => 1,
        ));

//        Sentry::getUserProvider()->create(array(
//            'email'    => 'Kristoffer@spason.se',
//            'password' => 'endofbadbooking',
//            'activated' => 1,
//        ));
//
//        Sentry::getUserProvider()->create(array(
//            'email'    => 'user@user.com',
//            'password' => 'isddev123',
//            'activated' => 1,
//        ));
    }

}