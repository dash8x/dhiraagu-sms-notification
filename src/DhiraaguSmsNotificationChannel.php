<?php

namespace Dash8x\DhiraaguSmsNotification;

use Dash8x\DhiraaguSms\DhiraaguSms;
use Dash8x\DhiraaguSmsNotification\Exceptions\CouldNotSendNotification;
use Exception;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Notifications\Notification;

class DhiraaguSmsNotificationChannel
{
    /**
     * @var DhiraaguSms
     */
    protected $dhiraagu_sms;
    /**
     * @var Dispatcher
     */
    protected $events;

    /**
     * DhiraaguSmsNotificationChannel constructor.
     *
     * @param DhiraaguSms $dhiraagu_sms
     * @param Dispatcher $events
     */
    public function __construct(DhiraaguSms $dhiraagu_sms, Dispatcher $events)
    {
        $this->dhiraagu_sms = $dhiraagu_sms;
        $this->events = $events;
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
        try {
            $to = $this->getTo($notifiable);
            $message = $notification->toDhiraagu($notifiable);

            if (is_string($message)) {
                $message = new DhiraaguSmsNotificationMessage($message, $to);
            }

            if (! $message instanceof DhiraaguSmsNotificationMessage) {
                throw CouldNotSendNotification::invalidMessageObject($message);
            }

            $response = $this->dhiraagu_sms->send($message->getNumbers(), $message->getMessage());
            $event = new NotificationSent($notifiable, $notification, 'dhiraagu', $response);
            $this->dispatchEvent($event);

            return $response;
        } catch (Exception $exception) {
            $event = new NotificationFailed($notifiable, $notification, 'dhiraagu', ['message' => $exception->getMessage(), 'exception' => $exception]);
            $this->dispatchEvent($event);
        }
    }

    /**
     * Get the address to send a notification to.
     *
     * @param mixed $notifiable
     * @return mixed
     * @throws CouldNotSendNotification
     */
    protected function getTo($notifiable)
    {
        if ($notifiable->routeNotificationFor('dhiraagu')) {
            return $notifiable->routeNotificationFor('dhiraagu');
        }

        if (isset($notifiable->phone_number)) {
            return $notifiable->phone_number;
        }

        throw CouldNotSendNotification::invalidReceiver();
    }

    /**
     * Dispatch event
     *
     * @param $event
     */
    protected function dispatchEvent($event)
    {
        if (function_exists('event')) { // Use event helper when possible to add Lumen support
            event($event);
        } else {
            $this->events->fire($event);
        }
    }
}
