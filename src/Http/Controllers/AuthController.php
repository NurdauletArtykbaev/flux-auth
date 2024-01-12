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
       $userExists =  $this->authService->requestOtp($request->phone);

        return response()->json(['data' => [
            'exists' => $userExists
        ]]);
    }

    public function login(VerifyOtpRequest $request)
    {

        $data = $this->authService->login($request->validated());

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

    public function logout(Request $request)
    {
//        $ref = new \ReflectionClass($request->user());
        $request->user()->tokens()->delete();

        return response()->json([
            'data' => null,
            'message' => 'Вы вышли из системы',
        ]);
    }
}
