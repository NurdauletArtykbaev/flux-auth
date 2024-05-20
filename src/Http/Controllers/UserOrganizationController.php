<?php

namespace Nurdaulet\FluxAuth\Http\Controllers;

use Nurdaulet\FluxAuth\Http\Requests\UserSaveOrganizationRequest;
use Nurdaulet\FluxAuth\Http\Resources\UserOrganizationResource;
use Illuminate\Http\Request;
use Nurdaulet\FluxAuth\Models\TemproryImage;

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
        if (! $user->is_owner) {
            return  response()->noContent();
        }
        $data = $request->validated();
        $data['user_id'] = $user->id;

        if (isset($data['temp_image_id'])) {
            $data['image']  = TemproryImage::find('temp_image_id')?->image;
        }

        $isSelectedExists = config('flux-auth.models.user_organization')::where('is_selected', true)
            ->where('user_id', $user->id)->exists();
        if (!$isSelectedExists) {
            $data['is_selected'] = true;
        }
        if (config('flux-auth.options.organization_default_status')) {
            $data['status'] = config('flux-auth.options.organization_default_status');
        }
        config('flux-auth.models.user_organization')::create($data);
        return response()->noContent();
    }

    public function show($id)
    {
        $user = auth()->guard('sanctum')->user();
        $organization = config('flux-auth.models.user_organization')::where('user_id', $user->id)
            ->with('typeOrganization','city')
            ->findOrFail($id);
        return new UserOrganizationResource($organization);
    }

    public function update($id, UserSaveOrganizationRequest $request)
    {

        $user = auth()->guard('sanctum')->user();
        $organization = config('flux-auth.models.user_organization')::where('user_id', $user->id)
            ->with('typeOrganization')
            ->findOrFail($id);
        $data = $request->validated();
//        $image = $request->input('image',null);
        if (isset($data['temp_image_id'])) {
            $data['image']  = TemproryImage::find('temp_image_id')?->image;
        }
//        $data['image'] = $request->input('image');
//        if (isset($data['image'])) {
//            $data['image']  = TemproryImage::find('temp_image_id')?->image;
//        }
        $organization->update($data);

        $this->checkIsSelecectOrganization($user);
        return response()->noContent();
    }

    private function checkIsSelecectOrganization($user)
    {
        $userOrganizations = config('flux-auth.models.user_organization')::where('user_id', 20)->get();
        if (!empty($userOrganizations)) {
            if (!$userOrganizations->where('is_selected', 1)->count()) {
                $org = $userOrganizations->first();
                $org->is_selected = 1;
                $org->save();
            }
        }
    }

    public function updateSelected($id)
    {

        $user = auth()->guard('sanctum')->user();
        $organization = config('flux-auth.models.user_organization')::where('user_id', $user->id)
            ->findOrFail($id);
        $organization->update(['is_selected' => true]);

        config('flux-auth.models.user_organization')::where('user_id', $user->id)
            ->where('id', '<>', $organization->id)
            ->update(['is_selected' => false]);
        return response()->noContent();
    }

    public function destroy($id, Request $request)
    {
        $user = auth()->guard('sanctum')->user();
        $organization = config('flux-auth.models.user_organization')::where('user_id', $user->id)
            ->with('typeOrganization')
            ->findOrFail($id);
        $organization->delete();
        $this->checkIsSelecectOrganization($user);
        return response()->noContent();
    }
}
