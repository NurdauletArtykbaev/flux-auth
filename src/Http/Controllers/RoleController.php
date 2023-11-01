<?php

namespace Nurdaulet\FluxAuth\Http\Controllers;

use Nurdaulet\FluxAuth\Http\Resources\RoleResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class RoleController
{
    public function __invoke(Request $request)
    {
        $roles = Cache::remember("roles", 269746, function ()  {
            return config('flux-auth.models.role')::orderBy('description')->get();
        });

        return RoleResource::collection($roles);
    }
}
