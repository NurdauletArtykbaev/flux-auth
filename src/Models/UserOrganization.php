<?php

namespace Nurdaulet\FluxAuth\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property boolean $is_main
 *
 * @property-read User $user
 */
class UserOrganization extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'form_organization',
        'bin_iin',
        'address',
        'birthdate',
        'full_name_head',
        'user_id',
        'type_organization_id',
        'certificate_register_ip',
        'recipient_invoice_bank',
        'recipient_invoice_bank_full_name',
        'bik',
        'iban',
        'recipient_invoice_address'
    ];

    public function typeOrganization()
    {
        return $this->belongsTo(TypeOrganization::class, 'type_organization_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
