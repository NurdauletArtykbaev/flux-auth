<?php

namespace Nurdaulet\FluxAuth\Http\Requests;

class VerifyOtpRequest extends PhoneNumberRequest
{

    public function rules()
    {
        return array_merge(parent::rules(), [
            'is_owner' => 'boolean',
            'code' => 'required|min:4'
        ]);
    }
}
