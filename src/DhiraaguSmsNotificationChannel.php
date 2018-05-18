<?php

namespace Dash8x\DhiraaguSmsNotification;

use Dash8x\DhiraaguSmsNotification\Exceptions\CouldNotSendNotification;
use Dash8x\DhiraaguSmsNotification\Events\MessageWasSent;
use Dash8x\DhiraaguSmsNotification\Events\SendingMessage;
use Illuminate\Notifications\Notification;

class DhiraaguSmsNotificationChannel
{
    public function __construct()
    {
        // Initialisation code here
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @throws \Dash8x\DhiraaguSmsNotification\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        //$response = [a call to the api of your notification send]

//        if ($response->error) { // replace this by the code need to check for errors
//            throw CouldNotSendNotification::serviceRespondedWithAnError($response);
//        }
    }
}
