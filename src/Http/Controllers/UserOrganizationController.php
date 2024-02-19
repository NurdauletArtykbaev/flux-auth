<?php

namespace Nurdaulet\FluxAuth\Http\Controllers;

use Nurdaulet\FluxAuth\Http\Requests\UserSaveOrganizationRequest;
use Nurdaulet\FluxAuth\Http\Resources\UserOrganizationResource;
use Nurdaulet\FluxAuth\Http\Resources\UserResource;
use Illuminate\Http\Request;

class UserOrganizationController
{
    public function index(Request $request)
    {
        $user = auth()->guard('sanctum')->user();
        $organizations = config('flux-auth.models.user_organization')::where('user_id', $user->id)
            ->with('typeOrganization')
            ->orderBy('name')->get();
        return UserOrganizationResource::collection($organizations);
    }

    public function store(UserSaveOrganizationRequest $request)
    {
        $user = auth()->guard('sanctum')->user();
        $data = $request->validated();
        $data['user_id'] = $user->id;
        config('flux-auth.models.user_organization')::create($data);
        return response()->noContent();
    }

    public function show($id)
    {
        $user = auth()->guard('sanctum')->user();
        $organization = config('flux-auth.models.user_organization')::where('user_id', $user->id)
            ->with('typeOrganization')
            ->findOrFail($id);
        return new UserOrganizationResource($organization);
    }

    public function update($id, UserSaveOrganizationRequest $request)
    {

        $user = auth()->guard('sanctum')->user();
        $organization = config('flux-auth.models.user_organization')::where('user_id', $user->id)
            ->with('typeOrganization')
            ->findOrFail($id);
        $organization->update($request->validated());
        return new UserResource($user);
    }

    public function destroy($id, Request $request)
    {
        $user = auth()->guard('sanctum')->user();
        $organization = config('flux-auth.models.user_organization')::where('user_id', $user->id)
            ->with('typeOrganization')
            ->findOrFail($id);
        $organization->delete();
        return response()->noContent();
    }
}
