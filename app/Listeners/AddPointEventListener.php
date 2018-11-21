<?php

namespace App\Listeners;

use App\Events\AddPointEvent;
use App\UserPoint;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;

class AddPointEventListener
{
     protected $pointData;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(UserPoint $userPoint)
    {
        $this->model = $userPoint;
    }

    /**
     * Handle the event.
     *
     * @param  Event  $event
     * @return void
     */
    public function handle(AddPointEvent $event)
    {
        // 0 = bridges, 1 = notes , 3 = bridge like , 4 = listner
        $userPoint = new UserPoint;
        $userPoint->user_id     = $event->pointData['user_id'];
        $userPoint->type        = $event->pointData['type'];
        $userPoint->point       = $event->pointData['point'];
        $userPoint->type_id     = $event->pointData['type_id'];
        $userPoint->save();
    }
}
