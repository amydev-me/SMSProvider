<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Http\Controllers\SendSmsWithAdminToken;

use App\Models\Balance;

use Mail;

class SendNhnBalanceAlert extends Command
{
	use SendSmsWithAdminToken;

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'send:nhn-low-balance';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Send Email and SMS alert when NHN balance is below 500,000';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		try {
			$balance = Balance::whereHas('purchase', function ($query) {
									$query->where('obsolete', 0)
											->where('out_of_balance', 0);
								})
								->first();

			if ($balance->balance < 500000) {
				for ($i = 0; $i < 5; $i++) {
					$this->sendSmsWithAdminToken('+9595074149', 'Low Balance Alert - ' . $balance->balance);
				}

				$email = 'heinchithlein@lfuturedev.com';

				$subject = 'Low Balance Alert';
				$text = 'NHN Balance is low - ' . $balance->balance;

				Mail::raw("{$text}", function($mail) use ($email, $subject) {
					$mail->to($email)
						->subject($subject);
				});
			}
		} catch (\Exception $e) {

		}
	}
}