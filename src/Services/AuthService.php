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
    public function __construct(private AuthRepository $authRepository)
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
                SmsKzFacade::to($phone)->text(env('app_name'). " код: $code")->send();
            }
        }

        Cache::put("$phone/code", $code, 120);
    }

    public function login($phoneNumber, $code)
    {
        return $this->authRepository->login($phoneNumber, $code);
    }

    public function register(User $user, $name, $surname)
    {
        return $this->authRepository->register($user, $name, $surname);
    }


}
