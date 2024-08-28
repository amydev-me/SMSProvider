<?php
namespace App\Listeners;

use App\Events\UserRegistered;
use App\Models\UserToken;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Web\Http\Controllers\TokenGenerate;

class CreateUserToken
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
        $user_token = new \stdClass();
        $user_token->user_id = $user->id;
        $user_token->app_name = $user->username;
        $user_token->api_key = $user->username;
        $user_token->api_secret = (new TokenGenerate())->generateSecret();
        
        UserToken::create((array)$user_token);
    }
}