<?php

namespace Dash8x\DhiraaguSmsNotification\Facades;

use Illuminate\Support\Facades\Facade;

class DhiraaguSms extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'dhiraagusms';
    }
}
