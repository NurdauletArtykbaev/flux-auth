<?php

namespace Nurdaulet\FluxAuth\Repositories;

use Illuminate\Database\Eloquent\Collection;

class UserRatingRepository
{
    public function create($data)
    {
        return config('flux-auth.models.user_rating')::create($data);
    }
}
