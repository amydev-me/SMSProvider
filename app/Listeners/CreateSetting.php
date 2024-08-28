<?php
namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Events\UserRegistered;
use App\Models\UserSetting;

use Carbon\Carbon;

class CreateSetting
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

		UserSetting::create([
			'user_id' => $user->id
		]);
	}
}