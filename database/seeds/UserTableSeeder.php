<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$admins = array(
				array('username' => 'lfuturedev', 'password' => bcrypt('password'), 'full_name' => 'L-Future', 'role' => 4),
				array('username' => 'logadmin', 'password' => bcrypt('password'), 'full_name' => 'Log Admin', 'role' => 1)
			);

		DB::table('admins')->insert($admins);

		$admin = \App\Models\Admin::first();

		DB::table('admin_tokens')->insert([
			'admin_id' => $admin->id,
			'app_name' => 'deliver',
			'api_key' => 'admin',
			'api_secret' => 'NGNhMDI0NmQxNjRlNTBiMWMxNDc2NTdkZmYzMjkyMThiN2EwZmUyZDc0YWU0MzM1'
		]);
	}
}