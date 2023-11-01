<?php

namespace Nurdaulet\FluxAuth\Repositories;


use Illuminate\Support\Facades\Cache;

class PermissionRepository
{

    public function get()
    {
        return Cache::remember("permissions", 3600, function () {
            return config('flux-auth.models.permission')::select('id', 'name', 'description')->get();
        });
    }

}
