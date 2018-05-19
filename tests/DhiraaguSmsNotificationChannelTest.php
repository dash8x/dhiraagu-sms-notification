<?php

namespace Dash8x\DhiraaguSmsNotification\Test;

use Dash8x\DhiraaguSms\DhiraaguSms;
use Dash8x\DhiraaguSms\DhiraaguSmsMessage;
use Dash8x\DhiraaguSmsNotification\DhiraaguSmsNotificationChannel;
use Illuminate\Notifications\Events\NotificationSent;
use Mockery;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Events\Dispatcher;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Illuminate\Notifications\Events\NotificationFailed;

class DhiraaguSmsNotificationChannelTest extends MockeryTestCase
{
    /** @var DhiraaguSmsNotificationChannel */
    protected $channel;

    /** @var DhiraaguSms */
    protected $dhiraagu_sms;

    /** @var Dispatcher */
    protected $dispatcher;

    /**
     * Set up
     */
    public function setUp()
    {
        parent::setUp();

        $this->dhiraagu_sms = Mockery::mock(DhiraaguSms::class);
        $this->dispatcher = Mockery::mock(Dispatcher::class);

        $this->channel = new DhiraaguSmsNotificationChannel($this->dhiraagu_sms, $this->dispatcher);
    }

    /** @test */
    public function it_will_not_send_a_message_without_known_receiver()
    {
        $notifiable = new Notifiable();
        $notification = Mockery::mock(Notification::class);

        $this->dispatcher->shouldReceive('fire')
            ->atLeast()->once()
            ->with(Mockery::type(NotificationFailed::class));

        $result = $this->channel->send($notifiable, $notification);

        $this->assertNull($result);
    }

    /** @test */
    public function it_will_send_a_sms_message_to_the_result_of_the_route_method_of_the_notifiable()
    {
        $notifiable = new NotifiableWithMethod();
        $notification = Mockery::mock(Notification::class);

        $notification->shouldReceive('toDhiraagu')->andReturn('Message text');

        $this->dhiraagu_sms->shouldReceive('send')
            ->atLeast()->once()
            ->with(['+1111111111'], 'Message text')
            ->andReturn(Mockery::type(DhiraaguSmsMessage::class));

        $this->dispatcher->shouldReceive('fire')
            ->atLeast()->once()
            ->with(Mockery::type(NotificationSent::class));

        $this->channel->send($notifiable, $notification);
    }

    /** @test */
    public function it_will_send_a_message_to_the_phone_number_attribute_of_the_notifiable()
    {
        $notifiable = new NotifiableWithAttribute();
        $notification = Mockery::mock(Notification::class);

        $notification->shouldReceive('toDhiraagu')->andReturn('Message text');

        $this->dhiraagu_sms->shouldReceive('send')
            ->atLeast()->once()
            ->with(['+22222222222'], 'Message text')
            ->andReturn(Mockery::type(DhiraaguSmsMessage::class));

        $this->dispatcher->shouldReceive('fire')
            ->atLeast()->once()
            ->with(Mockery::type(NotificationSent::class));

        $this->channel->send($notifiable, $notification);
    }
}

class Notifiable
{
    public $phone_number = null;

    public function routeNotificationFor()
    {
    }
}

class NotifiableWithMethod
{
    public function routeNotificationFor()
    {
        return '+1111111111';
    }
}

class NotifiableWithAttribute
{
    public $phone_number = '+22222222222';

    public function routeNotificationFor()
    {
    }
}

class NotifiableWithAlphanumericSender
{
    public $phone_number = '+33333333333';

    public function routeNotificationFor()
    {
    }

    public function canReceiveAlphanumericSender()
    {
        return true;
    }
}
