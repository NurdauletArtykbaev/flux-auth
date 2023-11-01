<?php

namespace Nurdaulet\FluxAuth\Http\Controllers;

use Nurdaulet\FluxAuth\Http\Resources\RoleResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ComplaintController
{
    public function store($id, Request $request)
    {
        config('flux-auth.models.complaint_user')::create([
            'complaint_reason_id' => $request->get('complaint_reason_id'),
            'user_id' => $id,
            'who_complaint_user_id' => auth()->guard('sanctum')->user()?->id,
            'comment' => $request->get('comment')
        ]);
        return response()->noContent();
    }
}
