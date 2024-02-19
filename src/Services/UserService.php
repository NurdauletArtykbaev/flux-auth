<?php

namespace Nurdaulet\FluxAuth\Services;

use Nurdaulet\FluxAuth\Helpers\UserHelper;
use Nurdaulet\FluxAuth\Helpers\UserVerifyHelper;
use Nurdaulet\FluxAuth\Repositories\PermissionRepository;
use Nurdaulet\FluxAuth\Repositories\UserRepository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Nurdaulet\FluxItems\Facades\ItemsFacade;
use Nurdaulet\FluxOrders\Facades\OrdersFacade;
use Nurdaulet\FluxWallet\Facades\WalletFacade;

class UserService
{

    public function __construct(
        private UserRepository       $userRepository,
        private PermissionRepository $permissionRepository,
    )
    {
    }

//    public function deliveryUpdate($user, $data): void
//    {
//        if (isset($data['city_ids'])) {
//
//            $user->deliveryCities()->sync($data['city_ids']);
//        }
//        if (isset($data['store_ids'])) {
//            if (!count($data['store_ids'])) {
//                $storeIds = [];
//            } else {
//                $stores = Store::whereIn('id', $data['store_ids'])->where('user_id', $user->id)->get();
//                $storeIds = $stores->pluck('id')->toArray();
//            }
//            $user->selfCallStores()->sync($storeIds);
//        }
//
//    }

    public function completeCooperation($user)
    {
//        Ad::where('user_id', $user->id)->delete();
//        $user->is_verified = User::NOT_VERIFIED;
        $this->userRepository->update($user, ['is_verified' => UserHelper::NOT_VERIFIED]);
//        $user->save();
//        $user->stores()->delete();
    }

    public function updateOnline($user, $enumOnline)
    {
        $user = $this->userRepository->find($user->id);
        $data = [
            'online' => $enumOnline
        ];
        if ($enumOnline == 1) {
            $data['last_online'] = now();
        }
        return $this->userRepository->update($user, $data);

    }

    public function find($id, $relations = [], $withCounts = [])
    {
        return $this->userRepository->find($id, $relations = [], $withCounts = []);
    }

    public function checkBanned($user)
    {
        if ($user->isBanned()) {
            abort(Response::HTTP_FORBIDDEN, (__('errors.banned_user')));
        }
    }

    public function uploadAvatar($user, $image)
    {
        $lastAvatar = $user->avatar;

        $path = $this->userRepository->uploadImageToCloud('avatar', $user->id, $image);

        $this->userRepository->update($user, ['avatar' => $path]);
        if ($lastAvatar) {
            $this->userRepository->deleteImageFromCloud($lastAvatar);
        }

        return $user->avatarUrl;
    }

    public function getUserProfile($user)
    {
//        $user = $this->userRepository->find($user->id);
        return $this->prepareProfileData($user);
    }
    public function saveOrganization($user,$data)
    {
//        $user = $this->userRepository->find($user->id);
        return $user->organization()->updateOrCreate([], $data);
    }

    private function prepareProfileData($user)
    {

//        $user->loadMissing(['roles' => fn($query) => $query->withPivot('lord_id', 'deleted_at', 'store_id')
//            ->with('pivot.lord')->wherePivotNull('deleted_at')]);
//        if ($user->roles->isNotEmpty()) {
//            $role = $user->roles->first();
//            $user->is_verified = $role->pivot->lord->is_verified;
//            $user->lord_balance = WalletFacade::getBalanceByUserId($role->pivot->lord_id);
//            $user->graphic_works = $role->pivot->lord->graphic_works;
//            $user->delivery_times = $role->pivot->lord->delivery_times;
//        }
//
//        if ($user->is_verified == UserVerifyHelper::VERIFIED) {
//            $user->monthly_ad_orders_count = OrdersFacade::getMonthlyOrders($user->id);
//            $user->items_count = ItemsFacade::countByUserId($user->id);
//        }
        if (config('flux-auth.options.is_enabled_balance')) {
            $user->balance = WalletFacade::getBalanceByUserId($user->id);
        }
        if ($user->is_owner) {


            $relations = config('flux-auth.options.is_multiple_organizations')
                ? 'organizations.typeOrganization'
                : 'organization.typeOrganization';
            $user->load($relations);

        }
//        if ($user->roles->isNotEmpty()) {
//            $permissions = $user->getAllPermissions();
//        } else {
//            $permissions = $this->permissionRepository->get();
//        }
        return $user;
    }

    public function saveContract($user, $file): void
    {
        $path = UserHelper::CONTRACT_DIR . Str::uuid() . '.' . $file->getClientOriginalExtension();

        Storage::disk(config('flux-auth.options.storage_disk'))->put($path, file_get_contents($file));

        $this->userRepository->update($user, ['contract' => $path]);
    }

    public function deleteContract($user): void
    {
        if (!empty($user->contract)) {
            Storage::disk(config('flux-auth.options.storage_disk'))->delete($user->contract);
        }
        $this->userRepository->update($user, ['contract' => null]);
    }

    public function delete($user)
    {
        $user->delete();
        // TODO: call event deleted listeners user balance
    }


    public function update($user, array $data)
    {
        return $this->userRepository->update($user, $data);
    }
}
