<?php

namespace Nurdaulet\FluxAuth\Services;

use Nurdaulet\FluxAuth\Repositories\UserAddressRepository;

class UserAddressService
{
    public function __construct(private UserAddressRepository $addressRepository)
    {
    }

    public function get($user, $isTypeStore = false)
    {
        return $this->addressRepository->get($user, $isTypeStore);
    }

    public function store($user, $data)
    {
        $hasMainAddress = config('flux-auth.models.user_address')::where('user_id', $user->id)
            ->where('is_main', true)
            ->exists();
        $data['is_main'] = !$hasMainAddress;
        $data['user_id'] = $user->id;
        return $this->addressRepository->store($data);
    }

    public function update($id, $data)
    {
        $userAddress = $this->addressRepository->find($id);
        $this->addressRepository->update($userAddress, $data);
    }

    public function updateMainAddress( $id)
    {
        $userAddress = $this->addressRepository->find($id);
        $this->addressRepository->updateMainAddress($userAddress);
    }

    public function delete($id)
    {
        $userAddress = $this->addressRepository->find($id);
        $this->addressRepository->delete($userAddress);
    }
}
