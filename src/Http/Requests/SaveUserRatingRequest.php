<?php

namespace Nurdaulet\FluxAuth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveUserRatingRequest extends FormRequest
{

    public function rules()
    {
        return [
            'grade' => 'required',
            'comment' => 'nullable',
        ];
    }
}
