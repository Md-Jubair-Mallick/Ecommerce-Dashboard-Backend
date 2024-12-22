<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseHelper;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authService;
    public function __construct(AuthService
    $authService)
    {
        $this->authService = $authService;
    }
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
                'role' => 'required|in:admin,editor,viewer',
            ];

            $validated = $req->validate($rules);

            $user = $this->authService->register($validated);
            return ResponseHelper::success($user, 'User created successfully', 201);
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 500);
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
            $validated = $req->validate($rules);
            $data = $this->authService->login($validated);

            return ResponseHelper::success($data, 'User logged in successfully', 200);
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 500);
        }
    }

    /**
     * Logout a user
     * */
    public function logout(Request $req)
    {
        try {
            $this->authService->logout($req);
            return ResponseHelper::success([], 'User logged out successfully', 200);
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 500);
        }
    }

    /**
     * Get the authenticated user
     */
    public function me(Request $req)
    {
        $user = $req->user();
        return ResponseHelper::success($user, 'User retrieved successfully', 200);
    }
}
