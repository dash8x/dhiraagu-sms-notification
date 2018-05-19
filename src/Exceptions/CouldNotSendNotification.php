<?php

namespace Dash8x\DhiraaguSmsNotification\Exceptions;

use Dash8x\DhiraaguSmsNotification\DhiraaguSmsNotificationMessage;

class CouldNotSendNotification extends \Exception
{
    /**
     * @return static
     */
    public static function serviceRespondedWithAnError($response)
    {
        return new static("Descriptive error message.");
    }

    /**
     * @param mixed $message
     *
     * @return static
     */
    public static function invalidMessageObject($message)
    {
        $className = get_class($message) ?: 'Unknown';
        return new static(
            "Notification was not sent. Message object class `{$className}` is invalid. It should
            be `".DhiraaguSmsNotificationMessage::class.'`');
    }

    /**
     * @return static
     */
    public static function invalidReceiver()
    {
        return new static(
            'The notifiable did not have a receiving phone number. Add a routeNotificationForDhiraagu
            method or a phone_number attribute to your notifiable.'
        );
    }
}
