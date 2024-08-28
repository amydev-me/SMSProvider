<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PackageSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$packages = array(
			array('packageName' => 'Free', 'credit' =>10, 'cost'=>0, 'currency_type' => 'MMK'),
			array('packageName' => 'Archon', 'credit' => 500, 'cost' => 15000, 'currency_type' => 'MMK'),
			array('packageName' => 'Legend', 'credit' => 1050, 'cost' => 30000, 'currency_type' => 'MMK'),
			array('packageName' => 'Ancient', 'credit' => 1800, 'cost' => 50000, 'currency_type' => 'MMK'),
			array('packageName' => 'Divine', 'credit' => 3800, 'cost' => 800000, 'currency_type' => 'MMK'),
			array('packageName' => 'Immortal', 'credit' => 8000, 'cost' => 1600000, 'currency_type' => 'MMK'),
		);

		DB::table('packages')->insert($packages);
	}
}