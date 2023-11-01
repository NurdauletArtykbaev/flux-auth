<?php

namespace Nurdaulet\FluxAuth\Http\Controllers;

use Nurdaulet\FluxAuth\Helpers\UserVerifyHelper;
use Nurdaulet\FluxAuth\Http\Requests\UpdateUserRequest;
use Nurdaulet\FluxAuth\Http\Requests\UserSaveContractRequest;
use Nurdaulet\FluxAuth\Http\Resources\AboutUserResource;
use Nurdaulet\FluxAuth\Http\Resources\UserResource;
use Nurdaulet\FluxAuth\Models\User;
use Nurdaulet\FluxAuth\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController
{
    public function __construct(private UserService    $userService
    )
    {
    }

    public function me(Request $request)
    {
        $user = $this->userService->getUserProfile($request->user());
        return new UserResource($user);
    }


    public function uploadAvatar(Request $request)
    {
        $user = $request->user();

        return response()->json(['data' => [
            'src' => $this->userService->uploadAvatar($user, $request->file('avatar'))
        ]]);
    }

    public function completeCooperation(Request $request)
    {
        $this->userService->completeCooperation($request->user());
        return response()->noContent();
    }


//    public function getDelivery(Request $request)
//    {
//        $user = $request->user();
//        $user->load(['deliveryCities:id,name','selfCallStores:id,name,address']);
//        return response()->json(['data' => [
//            'delivery_cities' => $user->deliveryCities,
//            'self_call_stores' => $user->selfCallStores,
//        ]]);
//    }


//    public function updateDelivery(UserDeliveryUpdateRequest $request)
//    {
//        $this->userService->deliveryUpdate($request->user(), $request->validated());
//        return response()->noContent();
//    }


    public function uploadVerifyImage(Request $request)
    {
        $user = $request->user();
        if ($user->is_verified == UserVerifyHelper::VERIFIED) {
            return response()->json([
                'data' => [
                    'message' => 'Вы уже прошли верификацию'
                ]
            ]);
        }
        return response()->json(['data' => [
            'src' => $request->file('identification') ? $this->userService->uploadVerifyImage($user, $request->file('identification')) : null
        ]]);
    }

    public function destroy(Request $request)
    {
        $this->userService->delete($request->user());
        return response()->noContent();
    }

    public function update(UpdateUserRequest $request)
    {
        $user = $request->user();
        $user = $this->userService->update($user, $request->validated());
//        return response()->json();

        return new UserResource($user);
    }

    // notification

//    public function storeDeviceToken(Request $request)
//    {
//        $user = $request->user();
////        $request->validate([
////            'device_token' => 'required'
////        ]);
//        $platform = DeviceTokenHelper::getPlatform($request->platform);
//        DeviceToken::updateOrCreate(
//            [
//                'user_id' => $user->id,
//                'platform' => $platform
//            ],
//            [
//                'device_token' => $request->get('device_token'),
//            ]
//        );
//
//        return response()->noContent();
//    }


//    public function deleteDeviceToken(Request $request)
//    {
//        $platform = DeviceTokenHelper::getPlatform($request->platform);
//        if (DeviceToken::where('user_id', $request->user()->id)->where('platform', $platform)->exists()) {
//            DeviceToken::where('user_id', $request->user()->id)->where('platform', $platform)->delete();
//        }
//        return response()->noContent();
//    }

    public function aboutUser(Request $request, $id)
    {

        $user = $this->userService->find($id, [], ['ratings']);
        return new AboutUserResource($user);
    }

    public function online(Request $request)
    {

        $user = $this->userService->updateOnline($request->user(), $request->input('online'));
        return response()->json([
            'data' => [
                'online_to_date' => $user->last_online->format('c')
            ]
        ]);
    }

    public function saveContract(UserSaveContractRequest $request): Response
    {
        $user = $request->user();

        $this->userService->saveContract($user,  $request->file('contract'));

        return response()->noContent();
    }

    public function destroyContract(): Response
    {
        $user = request()->user();

        $this->userService->deleteContract($user);

        return response()->noContent();
    }
}
