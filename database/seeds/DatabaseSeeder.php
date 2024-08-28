<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 *
	 * @return void
	 */
	public function run()
	{
		$this->call(PackageSeeder::class);
		$this->call(UserTableSeeder::class);

		$path = 'database/seeds/countries.sql';
		DB::unprepared(file_get_contents($path));
		$this->command->info('Country table seeded!');

		$this->call(OperatorSeeder::class);
		$this->call(OperatorDetailSeeder::class);

		$this->call(TelecomSeeder::class);

		$this->call(SettingSeeder::class);
	}
}