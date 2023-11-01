<?php

namespace Nurdaulet\FluxAuth\Http\Controllers;

use Illuminate\Http\Request;
use Nurdaulet\FluxAuth\Http\Requests\PhoneNumberRequest;
use Nurdaulet\FluxAuth\Http\Requests\VerifyOtpRequest;
use Nurdaulet\FluxAuth\Models\User;
use Nurdaulet\FluxAuth\Services\AuthService;

class AuthController
{
    public function __construct(private AuthService $authService)
    {
    }

    public function requestOtp(PhoneNumberRequest $request)
    {
        $this->authService->requestOtp($request->phone);

        return response()->json(['data' => null]);
    }

    public function login(VerifyOtpRequest $request)
    {
        $data = $this->authService->login($request->get('phone'), $request->get('code'));

        return response()->json([
            'data' =>[
                'token' => $data,
            ]
        ]);
    }


    public function refreshToken(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();
        $token = $user->createToken("API TOKEN")->plainTextToken;
        return response()->json([
            'token' => $token,
        ]);
    }


    public function register(Request $request)
    {
        $data = $this->authService->register($request->user(), $request->get('name'), $request->get('surname'));

        return response()->json([
            'data' => $data,
            'message' => 'Имя и Фамилия обновлены',
        ]);
    }

    public function logout(Request $request)
    {
        $ref = new \ReflectionClass($request->user());
        dd($request->user()->getAttributes(), (new User())->getAttributes());
        $request->user()->tokens()->delete();

        return response()->json([
            'data' => null,
            'message' => 'Вы вышли из системы',
        ]);
    }
}
