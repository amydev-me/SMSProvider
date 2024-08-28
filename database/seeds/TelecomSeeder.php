<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TelecomSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$telecoms = array(
			array('name' => 'NHN', 'description' => 'Local'),
			array('name' => 'Dexatel', 'description' => 'International')
		);

		DB::table('telecoms')->insert($telecoms);
	}
}