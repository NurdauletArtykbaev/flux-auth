<?php

namespace Nurdaulet\FluxAuth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name'      => 'nullable|string',
            'surname'   => 'nullable|string',
            'bik' => 'nullable',
            'iik' => 'nullable',
            'last_name'  => 'nullable|string', //TODO add last name and city id to users gender, lang, send_mail, sendnotification
            'city_id'   => 'nullable|integer',
            'born_date' => 'nullable|date',
            'type_organization_id' => 'nullable|integer',
            'company_name' => 'nullable|string',
            'bin_iin'   => 'nullable|string',
            'graphic_works' => 'nullable|array',
            'email'     => 'nullable|email|unique:users,email,' . $this->user()->id ,
            'phone'     => 'nullable|unique:users,phone,' . $this->user()->id ,
            'is_enabled_notification' => 'nullable|in:true,false,1,0',
            'delivery_times' => 'nullable|array',

//            'gender'    => 'nullable|in:m,f',
//            'lang'      => 'nullable|in:kk,en,ru',
//            'send_mail' => 'nullable|boolean',
//            'send_notification' => 'nullable|boolean',
        ];
    }
}
