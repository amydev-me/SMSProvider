<?php
namespace App\Listeners;

use App\Events\UserRegistered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;

class CreateContactGroup
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
        $groups = array(
            array('user_id' => $user->id, 'groupName' => 'Customers'),
            array('user_id' => $user->id, 'groupName' => 'Partners'),
            array('user_id' => $user->id, 'groupName' => 'Team#1'),
            array('user_id' => $user->id, 'groupName' => 'Team#2'),
        );
        
        DB::table('groups')->insert($groups);
    }
}