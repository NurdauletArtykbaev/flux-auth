<?php
namespace Nurdaulet\FluxAuth\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Nurdaulet\FluxAuth\Facades\StringFormatter;
use Nurdaulet\FluxAuth\Models\User;
use Nurdaulet\KzSmsProvider\Facades\SmsKzFacade;
use Nurdaulet\FluxAuth\Repositories\AuthRepository;

class AuthService
{
    public function __construct(private AuthRepository $authRepository,private UserService $userService)
    {
    }

    public function requestOtp($phone)
    {
        $phone = StringFormatter::onlyDigits($phone) ;
        $code = rand(1000, 9999);
        $user = User::wherePhone($phone)->first();

        if (!app()->isProduction()) {
            $code = Str::substr($phone, -4);
        } else {
            if (!empty($user) && $user->code) {
                $code = $user->code;
            }else  {
                SmsKzFacade::to($phone)->text(env('APP_NAME'). " код: $code")->send();
            }
        }

        Cache::put("$phone/code", $code, 120);
        return (boolean) $user?->id;
    }

    public function login($data)
    {
        [$token,  $user] = $this->authRepository->login($data);
        $user = $this->userService->getUserProfile($user);
        return [$token, $user];
    }
}
