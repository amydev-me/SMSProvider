<?php
namespace App\Listeners;

use App\Events\UserRegistered;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\UserBalance;

use App\Models\UserPackage;
use App\Models\Package;

use Carbon\Carbon;

class CreateFreePlan
{
	/**
	 * Create the event listener.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//
	}

	/**
	 * Handle the event.
	 *
	 * @param  UserRegistered  $event
	 * @return void
	 */
	public function handle(UserRegistered $event)
	{
		$user = $event->user;
		$package = Package::GetFree();

		if ($package) {
			UserPackage::create([
				'user_id' => $user->id,
				'package_id' => $package->id,
				'credit' => $package->credit,
				'total_credit' => $package->credit,
				'cost' => $package->cost,
				'payment_method'=>'Bank',
				'status'=>'paid',
				'order_date' => Carbon::now()
			]);

			UserBalance::create([
				'user_id' => $user->id,
				'balance' => $package->credit
			]);
		}
	}
}
