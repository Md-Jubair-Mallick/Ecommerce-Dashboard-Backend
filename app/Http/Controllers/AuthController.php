<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Admin: Register a new user
     */
    public function register(Request $req)
    {
        try {
            $rules = [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|unique:users,email',
                'password' => 'required|string|min:8',
                'role' => 'required|in:admin,user',
            ];

            $req->validate($rules);

            $user = User::create([
                'name' => $req->name,
                'email' => $req->email,
                'password' => Hash::make($req->password),
                'role' => $req->role,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User created successfully',
                'result' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
 * Login a user
 * */
public function login(Request $req)
{
    try {
        $rules = [
            'email' => 'required|string|email|exists:users,email',
            'password' => 'required|string|min:8',
        ];
        $req->validate($rules);
        $user = User::where('email', $req->email)->first();

        if (!$user || !Hash::check($req->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'The provided credentials are incorrect'
            ]);
        }
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'success' => true,
            'token' => $token,
            'token_type' => 'Bearer'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
}

    /**
     * Logout a user
     * */
    public function logout(Request $req)
    {
        $req->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully!']);
    }

    /**
     * Get the authenticated user
     */
    public function me(Request $req)
    {
        return response()->json(['user'=>$req->user()]);
    }
}
