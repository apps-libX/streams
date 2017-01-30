<?php

namespace RAD\Streams\Facades;

use Illuminate\Support\Facades\Facade;

class Streams extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'streams';
    }
}
