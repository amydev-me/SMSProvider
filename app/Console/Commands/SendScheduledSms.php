<?php
namespace App\Console\Commands;

use Illuminate\Http\Request;
use App\Http\Controllers\SendMessage;

use App\Models\ScheduleMessage;
use App\Models\UserBalance;
use App\Models\UserPackage;
use App\Models\SmsLog;
use App\Models\User;

use Lfuture\Sendsms\SmsServiceFacade;
use Lfuture\Sendsms\SmsService;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Console\Command;
use Carbon\Carbon;

class SendScheduledSms extends Command
{
	use SendMessage;

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'send:sms';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Send Scheduled Sms';

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
	public function handle(Request $request)
	{
		$current_time = Carbon::now()->timezone('Asia/Rangoon');

		$messages = ScheduleMessage::where('status', 'Waiting')
									->where('send_at', $current_time->format('Y-m-d H:i'))
									->get();

		foreach ($messages as $message) {
			if ($message->message_parts > 6) {
				$message->update(['status' => 'Failed', 'warn_message' => 'Operator only allowed 6 SMS.']);
				exit();
			}

			$contacts = $message->schedule_details->pluck('recipient')->toArray();

			if ($message['user_id'] != NULL) {
				$numbers = $this->getValidNumbers($contacts, $message->message_content, $message['user_id']);

				if ($message['sms_type'] == 'Package') {
					if ( $this->credits($message['user_id']) < $message['total_credit'] ) {
						$message->update(['status' => 'Failed', 'warn_message' => 'You need to purchase more Packages before you can create message.']);
						exit();
					}

					UserBalance::where('user_id', $message['user_id'])->decrement('balance', $numbers['total_credit']);
				}

			} else {

				$numbers = $this->getValidNumbers($contacts, $message->message_content);
			}

			$message['body'] = $message['message_content'];
			$message['to'] = $numbers['valid_numbers'];
			$request->merge( $message->toArray() );

			$response = $this->sendMessageToOperator( $request );
			$response = json_decode($response->getContent(), true);

			if ($response['code'] == 200) {
				ScheduleMessage::where('id', $message['id'])->update(['status' => 'Delivered']);
			}
		}
	}
}





	/*protected function usd_credits($user_id)
	{
		return UserPackage::where('user_id', $user_id)->where('status', 'paid')->sum('total_usd') - $this->usd_usage($user_id);
	}

	protected function usd_usage($user_id)
	{
		$logdetails = SmsLog::where('user_id', $user_id)->get();
		return $logdetails->sum('total_sms') * User::where('id', $user_id)->value('usd_rate');
	}*/

					/*else {
						if ( $this->usd_credits($message['user_id']) < $message['total_sms'] * User::where('id', $message['user_id'])->value('usd_rate') ) {
							$message->update(['status' => 'Failed', 'warn_message' => 'You need to purchase more USD before you can create message.']);
						}
						else {
							$message['body'] = $message['message_content'];
							$message['to'] = $this->phoneNumbers($message->schedule_details);
							$request->merge( $message->toArray() );

							$response = $this->sendMessageToOperator( $request );
							$response = json_decode($response->getContent(), true);

							if ($response['code'] == 200) {
								ScheduleMessage::where('id', $message['id'])->update(['status' => 'Delivered']);
							}
						}
					}*/


	/*private function phoneNumbers($schedule_details)
	{
		$phones = [];

		foreach ($schedule_details as $detail) {
			$phones[] = $detail->recipient;
		}

		return $phones;
	}*/