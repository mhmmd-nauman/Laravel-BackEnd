<?php

class AvailableWeekdaysSeeder extends Seeder {

    // Reverse available weekdays in packages
	public function run()
	{
        $packages = Package::all();

        if ( !empty($packages) ) {

            foreach($packages as $package) {

                $package_available_weekdays = array();

                if ( !empty($package->available_weekdays) ) {
                    $package_available_weekdays = explode('|:|', rtrim($package->available_weekdays, '|:|'));

                    foreach($package_available_weekdays as $k => $wday) {
                        switch($wday) {
                            case 'Mon':
                                $package_available_weekdays[$k] = '1';
                                break;
                            case 'Tue':
                                $package_available_weekdays[$k] = '2';
                                break;
                            case 'Wed':
                                $package_available_weekdays[$k] = '3';
                                break;
                            case 'Thu':
                                $package_available_weekdays[$k] = '4';
                                break;
                            case 'Fri':
                                $package_available_weekdays[$k] = '5';
                                break;
                            case 'Sat':
                                $package_available_weekdays[$k] = '6';
                                break;
                            case 'Sun':
                                $package_available_weekdays[$k] = '0';
                                break;
                        }
                    }

                    $reverse_week_days = array(
                        '1' => 'Mon',
                        '2' => 'Tue',
                        '3' => 'Wed',
                        '4' => 'Thu',
                        '5' => 'Fri',
                        '6' => 'Sat',
                        '0' => 'Sun',
                    );

                    foreach($package_available_weekdays as $available_day) {
                        unset($reverse_week_days[$available_day]);
                    }

                    $package->available_weekdays = implode('|:|', $reverse_week_days);
                    $package->save();
                }
            }
        }
	}

}