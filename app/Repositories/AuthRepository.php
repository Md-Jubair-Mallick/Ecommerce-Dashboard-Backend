<?php

namespace App\Repositories;

use App\Http\Helpers\ResponseHelper;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthRepository
{
    public function register($validated)
    {
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);
        $user = User::create($validated);
        return $user;
    }

    public function login($validated)
    {
        $user = User::where('email', $validated['email'])->first();

        return (
                !$user || !Hash::check($validated['password'], $user->password)
            )
            ? false : $user;
    }

    public function logout($token)
    {
        return $token->delete();
    }
    }