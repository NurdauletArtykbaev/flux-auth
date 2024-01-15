<?php

namespace Nurdaulet\FluxAuth\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TypeOrganizationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug
        ];
    }
}
