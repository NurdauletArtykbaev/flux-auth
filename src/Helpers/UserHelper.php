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

}
