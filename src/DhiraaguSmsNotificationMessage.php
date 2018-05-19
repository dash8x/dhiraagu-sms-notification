<?php

namespace Dash8x\DhiraaguSmsNotification;

class DhiraaguSmsNotificationMessage
{
    /**
     * The numbers that the message was sent to
     * @var array
     */
    protected $numbers = [];

    /**
     * The message
     * @var string
     */
    protected $message;

    /**
     * Dhiraagu Sms Notification Message constructor.
     *
     * @param string $message
     * @param array|string $numbers
     */
    public function __construct($message, $numbers)
    {
        $this->setMessage($message);
        $this->setNumbers($numbers);
    }
    
    /**
     * Set message
     *
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set numbers
     *
     * @param string|array $numbers
     */
    public function setNumbers($numbers)
    {
        if (! is_array($numbers)) {
            $numbers = [$numbers];
        }

        $this->numbers = $numbers;
    }

    /**
     * Get numbers
     *
     * @return array
     */
    public function getNumbers()
    {
        return $this->numbers;
    }
}
