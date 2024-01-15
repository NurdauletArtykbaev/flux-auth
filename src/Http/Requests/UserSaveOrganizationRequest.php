<?php

declare(strict_types=1);

namespace Nurdaulet\FluxAuth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UserSaveOrganizationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => "required",
            'form_organization' => "nullable",
            'bin_iin' => "nullable",
            'address' => "nullable",
            'birthdate' =>  ["nullable", 'date', 'date_format:Y-m-d'],
            'full_name_head' => "nullable",
            'type_organization_id' => "required",
            'certificate_register_ip' => "nullable",
            'recipient_invoice_bank' => "nullable",
            'recipient_invoice_bank_full_name' => "nullable",
            'bik' => "nullable",
            'iban' => "nullable",
            'recipient_invoice_address' => "nullable",
        ];
    }
}
