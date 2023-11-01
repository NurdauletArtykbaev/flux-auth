<?php

namespace Nurdaulet\FluxAuth\Facades;

use Illuminate\Support\Facades\Facade;

class StringFormatter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'stringFormatter';
    }
}