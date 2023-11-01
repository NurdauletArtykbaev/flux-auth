<?php

namespace Nurdaulet\FluxAuth\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserRoleResource extends JsonResource
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
            'description' => $this->description,
            'lord_id' => $this->pivot->lord_id,
            'store_d' => $this->pivot->store_id,
            'lord' => $this->getLord(),
            'store' => $this->getStore(),

        ];
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
