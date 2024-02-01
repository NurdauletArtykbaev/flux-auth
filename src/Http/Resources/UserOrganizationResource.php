<?php

namespace Nurdaulet\FluxAuth\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Nurdaulet\FluxAuth\Helpers\UserOrganizationHelper;

class UserOrganizationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'name' => $this->name,
            'form_organization' => $this->form_organization,
            'bin_iin' => $this->bin_iin,
            'address' => $this->address,
            'birthdate' => $this->birthdate,
            'full_name_head' => $this->full_name_head,
            'type_organization' => new TypeOrganizationResource($this->typeOrganization),
            'certificate_register_ip' => $this->certificate_register_ip,
            'recipient_invoice_bank' => $this->recipient_invoice_bank,
            'recipient_invoice_bank_full_name' => $this->recipient_invoice_bank_full_name,
            'bik' => $this->bik,
            'iban' => $this->iban,
            'recipient_invoice_address' => $this->recipient_invoice_address,
            'status_raw' => UserOrganizationHelper::STATUS_RAWS[$this->status] ?? UserOrganizationHelper::STATUS_FAILED,
            'message' => $this->message,
            'end_check_hour' => $this->calculateEndCheckHour()
        ];
    }

    private function calculateEndCheckHour()
    {
        $checkHour = config('flux-auth.options.organization_check_hour', 12);
        return $this->status == UserOrganizationHelper::STATUS_WAITING
            ? (Carbon::now()->diffInHours(Carbon::create($this->updated_at)) < $checkHour)
                ? (config('flux-auth.options.organization_check_hour', 12) - Carbon::now()->diffInHours(Carbon::create($this->updated_at)) )
                : 0
            : 0;
    }

    private function getLord()
    {
        $lord = null;
        if ($this->pivot?->lord && $this->pivot?->lord?->id) {
            $lord = [
                'id' => $this->pivot->lord->id,
                'name' => $this->pivot->lord->name,
                'last_name' => $this->pivot->lord->last_name,
                'avatar' => $this->pivot->lord->avatar ? config('filesystems.disks.s3.url') . '/' . $this->pivot->lord->avatar : null,
                'avatar_color' => $this->pivot->lord->avatar_color,
                'surname' => $this->pivot->lord->surname,
                'company_name' => $this->pivot->lord->company_name,
            ];
        }
        return $lord;
    }

    private function getStore()
    {

        $store = null;
        if ($this->pivot?->store && $this->pivot?->store?->id) {
            $store = [
                'id' => $this->pivot->store->id,
                'name' => $this->pivot->store->name,
                'address' => $this->pivot->store->address,
                'city_id' => $this->pivot->store->city_id,
            ];
        }
        return $store;
    }
}
