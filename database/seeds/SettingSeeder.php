<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$setting = [
			[
				'sender' => 'mmSMSPortal',
				'facebook_url' => 'https://www.facebook.com/triplesms',
				'phones' => '+95 9 5074149',
				'email' => 'info@triplesms.com'
			]
		];

		DB::table('default_settings')->insert($setting);
	}
}