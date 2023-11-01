<?php

declare(strict_types=1);

namespace Nurdaulet\FluxAuth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UserSaveContractRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'contract' => 'required|file|mimes:pdf,docx|max:2048',
        ];
    }
}
