<?php

namespace Nurdaulet\FluxAuth\Repositories;

use Illuminate\Database\Eloquent\Collection;

class UserAddressRepository
{
    public function find($id)
    {
        return config('flux-auth.models.user_address')::findOrFail($id);

    }
    public function get( $user, $isTypeStore = false)
    {
        return config('flux-auth.models.user_address')::where('user_id', $user->id)
            ->where('is_type_store', (int) $isTypeStore)
            ->orderBy('name')
            ->get();
    }

    public function store($data)
    {
        return config('flux-auth.models.user_address')::create($data);
    }

    public function update($userAddress, $data)
    {
        $userAddress->fill($data);
        $userAddress->saveOrFail();
    }

    public function updateMainAddress($userAddress)
    {
        $currentUser = $userAddress->user;

        config('flux-auth.models.user_address')::where('user_id', $currentUser->id)
            ->update(['is_main' => false]);

        $userAddress->is_main = true;
        $userAddress->save();

    }

    public function delete($userAddress)
    {
        if ($userAddress->is_main) {
            $latestAddress =  config('flux-auth.models.user_address')::where('user_id', $userAddress->user_id)
                ->where('id', '<>', $userAddress->id)
                ->orderBy('created_at', 'desc')
                ->first();
            $latestAddress?->update(['is_main' => true]);
        }
        $userAddress->delete();
    }
}
