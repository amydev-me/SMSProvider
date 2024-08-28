<?php
namespace App\Listeners;

use App\Events\UserRegistered;
use App\Models\VerifyUserEmail;
use App\Notifications\UserAccountVerify;
use Carbon\Carbon;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEmailConfirmation
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

        $verify = VerifyUserEmail::create([
            'user_id' => $user->id,
            'token' => str_random(40),
            'expire_at' => Carbon::now()->addHour(24),
            'resend_at' => Carbon::now(),
            'resent_in' => 4
        ]);
        
        $user->notify(new UserAccountVerify($user, $verify->token));
    }
}