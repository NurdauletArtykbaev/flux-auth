<?php

namespace Nurdaulet\FluxAuth\Helpers;


class UserHelper
{

    /*delete ? IDENTIFY_STATUS */
    const IDENTIFY_STATUS_DEFAULT = 0;
    const IDENTIFY_STATUS_IN_CHECK = 1;
    const IDENTIFY_STATUS_SUCCESS = 2;
    const IDENTIFY_STATUS_FAILED = 3;
    const TEMPT_CONTRACT_DIR = 'temp_contracts/';
    const CONTRACT_DIR = 'contracts/';

    const NOT_VERIFIED = 1;
    const ON_PROCESS = 2;
    const VERIFIED = 3;
    const REJECTED = 4;
    const MODERATION_STATUS_RAWS = [
        self::NOT_VERIFIED  => 'not_verified',
        self::ON_PROCESS  => 'on_verified',
        self::VERIFIED  => 'verified',
        self::REJECTED  => 'rejected',
    ];



    public static function getVerifiedOptions()
    {
        return [
            self::NOT_VERIFIED => trans('admin.not_verified'),
            self::ON_PROCESS => trans('admin.on_process'),
            self::VERIFIED => trans('admin.verified'),
        ];
    }
}
