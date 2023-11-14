<?php

namespace Nurdaulet\FluxAuth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveUserAddressRequest extends FormRequest
{

    public function rules()
    {
        return [
            'city_id' => 'required|int|exists:cities,id',
            'name' => 'nullable|string',
            'address' => 'required|string',
            'is_type_store' => 'nullable',
            'house' => 'nullable',
            'floor' => 'nullable|int',
            'apartment' => 'nullable',
            'lat' => 'nullable|string',
            'lng' => 'nullable|string',
        ];
    }
}
