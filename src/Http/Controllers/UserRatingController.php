<?php

namespace Nurdaulet\FluxAuth\Http\Controllers;

use Illuminate\Http\Request;
use Nurdaulet\FluxAuth\Http\Requests\SaveUserRatingRequest;
use Nurdaulet\FluxAuth\Services\UserRatingService;

class UserRatingController
{
    public function __construct(private UserRatingService $userRatingService)
    {
    }

    public function store($id, SaveUserRatingRequest $request)
    {
        $this->userRatingService->create($id, $request->validated());
        return response()->noContent();
    }
}
