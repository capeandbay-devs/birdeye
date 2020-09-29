<?php

namespace CapeAndBay\BirdEye\Facades;

use Illuminate\Support\Facades\Facade;

class BirdEye
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'birdeye';
    }
}
