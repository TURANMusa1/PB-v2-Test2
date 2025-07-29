<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\UseCases\LoginUseCase;
use App\UseCases\RegisterUseCase;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct(
        private LoginUseCase $loginUseCase,
        private RegisterUseCase $registerUseCase,
        private AuthService $authService
    ) {}

    /**
     * Login user
     */
    public function login(Request $request): JsonResponse
    {
        try {
            $result = $this->loginUseCase->execute($request);

            return response()->json([
                'user' => $result['user'],
                'token' => $result['token'],
                'token_type' => $result['token_type'],
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Login failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Register user
     */
    public function register(Request $request): JsonResponse
    {
        try {
            $result = $this->registerUseCase->execute($request);

            return response()->json([
                'user' => $result['user'],
                'token' => $result['token'],
                'token_type' => $result['token_type'],
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registration failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Logout user
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $this->authService->logout($user);

            return response()->json([
                'success' => true,
                'message' => 'Logout successful',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Logout failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get current user
     */
    public function me(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $currentUser = $this->authService->getCurrentUser($user);

            return response()->json([
                'data' => $currentUser,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get user data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Refresh token
     */
    public function refresh(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $result = $this->authService->refreshToken($user);

            return response()->json([
                'success' => true,
                'message' => 'Token refreshed successfully',
                'data' => [
                    'user' => $result['user'],
                    'token' => $result['token'],
                    'token_type' => $result['token_type'],
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token refresh failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
} 