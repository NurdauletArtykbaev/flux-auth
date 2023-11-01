<?php

namespace Nurdaulet\FluxAuth\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {


        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'last_name' => $this->last_name,
            'surname' => $this->surname,
            'avatar' => $this->avatar ? config('filesystems.disks.s3.url').'/'.$this->avatar : null,
            'avatar_color' => $this->avatar_color,
            'phone' => $this->phone,
//            'logo_url' => $this->logoUrl,
//            'graphic_works' => $this->graphic_works ?: [] ,
//            'delivery_times' => $this->delivery_times ?: [] ,
//            'logo_webp_url' => $this->logoWebpUrl,
//            'type_organization_id' => $this->type_organization_id,
            'company_name' => $this->company_name,
            'bin_iin' => $this->bin_iin,
            'city_id' => $this->city_id,
//            'born_date' => $this->born_date,
//            'bik' => $this->bik,
//            'iik' => $this->iik,
            'is_verified' => $this->is_verified,
            'is_identified' => $this->is_identified,
            'is_enabled_notification' => $this->is_enabled_notification,
//            'role' => $this->whenLoaded('roles', function () {
//                return new UserRoleResource($this->roles->first());
//            }),
//            'items_count' => $this->whenHas('items_count', fn() => $this->items_count),
//            'monthly_ad_orders_count' => $this->whenHas('monthly_ad_orders_count', $this->monthly_ad_orders_count),
//            'permissions' => $this->when(isset($this->permissions), $this->permissions),
            'balance' => $this->whenHas('balance', function () {
                return [
                    'money' => $this->balance->money,
                    'bonus' => $this->balance->bonus,
                ];
            }),
//            'lord_balance' => $this->whenHas('lord_balance',function () {
//                return [
//                    'money' => $this->lord_balance->money,
//                    'bonus' => $this->lord_balance->bonus,
//                ];
//            }),
        ];
    }
}
