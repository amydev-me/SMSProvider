<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OperatorDetailSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$operator_details = array(
			array('country_id' => 152, 'operator_id' => 1, 'starting_number' => '95979'),
			array('country_id' => 152, 'operator_id' => 1, 'starting_number' => '95978'),
			array('country_id' => 152, 'operator_id' => 2, 'starting_number' => '95997'),
			array('country_id' => 152, 'operator_id' => 2, 'starting_number' => '95996'),
			array('country_id' => 152, 'operator_id' => 3, 'starting_number' => '95933'),
			array('country_id' => 152, 'operator_id' => 4, 'starting_number' => '95969'),
		);

		DB::table('operator_details')->insert($operator_details);
	}
}