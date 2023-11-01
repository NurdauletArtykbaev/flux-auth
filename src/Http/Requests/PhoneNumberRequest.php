<?php

namespace Nurdaulet\FluxAuth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PhoneNumberRequest extends FormRequest
{
    public function rules()
    {
        return [
            'phone' => 'required|regex:/^(\s*)?(\+)?([- _():=+]?\d[- _():=+]?){11,11}(\s*)?$/'
        ];
    }
}
