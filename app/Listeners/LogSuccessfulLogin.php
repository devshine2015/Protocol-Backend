<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;

class LogSuccessfulLogin
{
     
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    /**
     * Handle the event.
     *
     * @param  Event  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $updateLogin = \App\User::where('email',Auth::user()->email)->update(["isloggedOut"=>1]);
    }
}
