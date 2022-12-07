<?php

namespace App\Http\Controllers;

use App\Http\Requests\auth\ChangePasswordRequest;
use App\Http\Requests\auth\LoginRequest;
use App\Http\Requests\auth\RegisterRequest;
use App\Repository\AuthService;
use Exception;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService)
    {
    }

    public function register(RegisterRequest $request)
    {
        $user = $this->authService->register($request);

        $token = Auth::attempt([
            'user_name' => $user->user_name,
            'password' => $user->password
        ]);

        return $this->successResponse(
            null,
            'Successfully registered',
            200,
            $token
        );
    }

    public function login(LoginRequest $request)
    {
        if (!$token = Auth::attempt($request->validated())) {
            throw new Exception('Invalid credentials', 401);
        }

        return $this->successResponse(
            null,
            'Logged in successfully',
            200,
            $token
        );
    }


    public function logout()
    {
        Auth::logout();
        return $this->successResponse(
            null,
            'Logged out successfully'
        );
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $this->authService->changePassword($request->validated());

        return $this->successResponse(
            null,
            'Password changed successfully'
        );
    }
}
