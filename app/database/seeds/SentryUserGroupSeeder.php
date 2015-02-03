<?php

class SentryUserGroupSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        DB::table('users_groups')->delete();

//        $userUser = Sentry::getUserProvider()->findByLogin('user@user.com');
        $adminUser = Sentry::getUserProvider()->findByLogin('Caroline@spason.se');
        $admin1User = Sentry::getUserProvider()->findByLogin('Magnus@spason.se');
        $admin2User = Sentry::getUserProvider()->findByLogin('Kristoffer@spason.se');

        $userGroup = Sentry::getGroupProvider()->findByName('Users');
        $adminGroup = Sentry::getGroupProvider()->findByName('Admins');

        // Assign the groups to the users
//        $userUser->addGroup($userGroup);
        $adminUser->addGroup($userGroup);
        $adminUser->addGroup($adminGroup);
        $admin1User->addGroup($userGroup);
        $admin1User->addGroup($adminGroup);
        $admin2User->addGroup($userGroup);
        $admin2User->addGroup($adminGroup);
    }

}