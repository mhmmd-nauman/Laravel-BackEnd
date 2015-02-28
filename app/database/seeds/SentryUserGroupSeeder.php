<?php

class SentryUserGroupSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $adminUser = Sentry::getUserProvider()->findByLogin('mhmmd.nauman@gmailcom');
        $normalUser = Sentry::getUserProvider()->findByLogin('mhmmd.user@gmail.com');
        

        $userGroup = Sentry::getGroupProvider()->findByName('Users');
        $adminGroup = Sentry::getGroupProvider()->findByName('Admins');

        // Assign the groups to the users
//        $userUser->addGroup($userGroup);
        $adminUser->addGroup($userGroup);
        $adminUser->addGroup($adminGroup);
        $normalUser->addGroup($userGroup);
        
    }

}