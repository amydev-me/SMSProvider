<?php
namespace App\Listeners;

use App\Events\UserRegistered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Mail;

class SendAdminNoti
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
	 * @param  UserRegistered $event
	 * @return void
	 */
	public function handle(UserRegistered $event)
	{
		$user = $event->user;

		Mail::send('admin-mail.admin-noti', compact('user'), function($message) {
			$message->to('moeshan@lfuturedev.com', 'Win Laet Moe Shan')
					->cc('heinchithlein@gmail.com', 'Hein Chit Hlein')
					->subject('TripleSMS User Registration');
		});
	}
}
