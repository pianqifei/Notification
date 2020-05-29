<?php

namespace Pqf\Notification\Facades;

use Illuminate\Support\Facades\Facade;

class PqfNotification extends Facade
{
    /**
     * Return the facade accessor.
     *
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return 'pqf_notification';
    }
}
