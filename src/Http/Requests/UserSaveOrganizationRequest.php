<?php

declare(strict_types=1);

namespace Nurdaulet\FluxAuth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UserSaveOrganizationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => "nullable",
            'phone' => "nullable",
            'email' => "nullable",
            'image' => "nullable",
            'city_id' => "nullable",
            'temp_image_id' => "nullable",
            'form_organization' => "nullable",
            'bin_iin' => "nullable",
            'address' => "nullable",
            'birthdate' =>  ["nullable", 'date', 'date_format:Y-m-d'],
            'full_name_head' => "nullable",
            'type_organization_id' => "required",
            'certificate_register_ip' => "nullable",
            'recipient_invoice_bank' => "nullable",
            'recipient_invoice_bank_full_name' => "nullable",
            'is_selected' => "nullable",
            'field_activity' => "nullable",
            'bik' => "nullable",
            'iban' => "nullable",
            'recipient_invoice_address' => "nullable",
        ];
    }
}
