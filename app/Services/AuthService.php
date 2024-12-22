<?php

namespace App\Services;

use App\Repositories\AuthRepository;

class AuthService
{
    protected $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function register($validated)
    {
        return $this->authRepository->register($validated);
    }

    public function login($validated)
    {
        $user = $this->authRepository->login($validated);
        if ($user === false) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
        $token = $user->createToken('auth_token')->plainTextToken;
        $result = [
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer',
        ];
        return $result;
    }

    public function logout($req)
    {
        $token = $req->user()->currentAccessToken();
        return $this->authRepository->logout($token);
    }
}
