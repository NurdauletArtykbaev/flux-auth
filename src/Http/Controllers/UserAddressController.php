<?php

namespace Nurdaulet\FluxAuth\Http\Controllers;

use Illuminate\Http\Request;
use Nurdaulet\FluxAuth\Http\Requests\SaveUserAddressRequest;
use Nurdaulet\FluxAuth\Services\UserAddressService;
use Nurdaulet\FluxAuth\Http\Resources\UserAddressesResource;

class UserAddressController
{
    public function __construct(private UserAddressService $addressService)
    {
    }

    public function index(Request $request)
    {
        $addresses = $this->addressService->get($request->user(), $request->get('is_type_store', false));
        return UserAddressesResource::collection($addresses);
    }


    public function store(SaveUserAddressRequest $request)
    {
        $user = $request->user();

        $this->addressService->store($user, $request->validated());
        return response()->noContent();
    }

    public function update(SaveUserAddressRequest $request, $id)
    {
        $this->addressService->update($id, $request->validated());
        return response()->noContent();
    }

    public function updateMainAddress($id)
    {
        $this->addressService->updateMainAddress($id);
        return response()->noContent();
    }

    public function destroy( $id)
    {
        $this->addressService->delete($id);
        return response()->noContent();
    }
}
