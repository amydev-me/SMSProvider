<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OperatorSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$operators = array(
			array('country_id' => 152, 'name' => 'Telenor'),
			array('country_id' => 152, 'name' => 'Ooredoo'),
			array('country_id' => 152, 'name' => 'MEC'),
			array('country_id' => 152, 'name' => 'MyTel'),
			array('country_id' => 152, 'name' => 'MPT'),
		);

		DB::table('operators')->insert($operators);
	}
}