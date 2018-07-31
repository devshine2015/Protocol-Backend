<?php

namespace App\Listeners;

use App\Events\AddNotificationEvent;
use App\NotificationActivity;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;

class AddNotificationEventListener
{
     protected $notification;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(NotificationActivity $notification_activity)
    {
        $this->model = $notification_activity;
    }

    /**
     * Handle the event.
     *
     * @param  Event  $event
     * @return void
     */
    public function handle(AddNotificationEvent $event)
    {
        $userActivity = new NotificationActivity;
        $userActivity->type_id         = $event->notification->id;
        $userActivity->type            = $event->notification->type;
        $userActivity->created_by      = $event->notification->created_by;
        $userActivity->save();
    }
}
