<?php

namespace Nurdaulet\FluxAuth\Helpers;

class StringFormatterHelper
{


    public static function onlyDigits($str)
    {
        return preg_replace('/[^0-9]/', '', $str);
    }
}