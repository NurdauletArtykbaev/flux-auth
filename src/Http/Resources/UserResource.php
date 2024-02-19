<?php

namespace Nurdaulet\FluxAuth\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Nurdaulet\FluxAuth\Helpers\UserHelper;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        $moderationStatus = $this->is_verified ?? UserHelper::NOT_VERIFIED;
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'last_name' => $this->last_name,
            'surname' => $this->surname,
            'avatar' => $this->avatar ? config('filesystems.disks.s3.url') . '/' . $this->avatar : null,
            'avatar_color' => $this->avatar_color,
            'avg_rating' => (string)($this->avg_rating <= 0 ? 0 : $this->avg_rating),
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
            'moderation_status' => $moderationStatus,
            'moderation_status_raw' => UserHelper::MODERATION_STATUS_RAWS[$moderationStatus],
            'is_identified' => (bool)$this->is_identified,
            'is_owner' => (bool)$this->is_owner,
//            'is_enabled_notification' => $this->is_enabled_notification,
//            'role' => $this->whenLoaded('roles', function () {
//                return new UserRoleResource($this->roles->first());
//            }),
//            'monthly_ad_orders_count' => $this->whenHas('monthly_ad_orders_count', $this->monthly_ad_orders_count),
//            'permissions' => $this->when(isset($this->permissions), $this->permissions),
            'balance' => $this->whenHas('balance', function () {
                return [
                    'money' => (int)$this->balance->money,
                    'bonus' => (int)$this->balance->bonus,
                ];
            }),
            'organization' => $this->whenLoaded('organization', function () {
                return new UserOrganizationResource($this->organization);
            }),
            'organizations' => $this->whenLoaded('organizations', function () {
                return UserOrganizationResource::collection($this->organizations);
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
