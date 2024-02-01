<?php

namespace Nurdaulet\FluxAuth\Helpers;


class UserOrganizationHelper
{
    const STATUS_WAITING = 0;
    const STATUS_FAILED = 1;
    const STATUS_SUCCESS = 2;

    const STATUS_RAWS = [
        self::STATUS_WAITING  => 'waiting',
        self::STATUS_FAILED  => 'failed',
        self::STATUS_SUCCESS  => 'success',
    ];
}
