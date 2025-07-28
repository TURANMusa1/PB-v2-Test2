<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\JsonResponse;

class AuthServiceProxy
{
    private string $authServiceUrl;

    public function __construct()
    {
        $this->authServiceUrl = env('AUTH_SERVICE_URL', 'http://localhost:8002');
    }

    /**
     * Forward login request to auth service
     */
    public function login(Request $request): JsonResponse
    {
        try {
            $response = Http::post($this->authServiceUrl . '/api/auth/login', [
                'email' => $request->input('email'),
                'password' => $request->input('password'),
            ]);

            return response()->json($response->json(), $response->status());

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Auth service unavailable',
                'error' => $e->getMessage(),
            ], 503);
        }
    }

    /**
     * Forward register request to auth service
     */
    public function register(Request $request): JsonResponse
    {
        try {
            $response = Http::post($this->authServiceUrl . '/api/auth/register', $request->all());

            return response()->json($response->json(), $response->status());

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Auth service unavailable',
                'error' => $e->getMessage(),
            ], 503);
        }
    }

    /**
     * Forward logout request to auth service
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $token = $request->bearerToken();
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->post($this->authServiceUrl . '/api/auth/logout');

            return response()->json($response->json(), $response->status());

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Auth service unavailable',
                'error' => $e->getMessage(),
            ], 503);
        }
    }

    /**
     * Forward me request to auth service
     */
    public function me(Request $request): JsonResponse
    {
        try {
            $token = $request->bearerToken();
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->get($this->authServiceUrl . '/api/auth/me');

            return response()->json($response->json(), $response->status());

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Auth service unavailable',
                'error' => $e->getMessage(),
            ], 503);
        }
    }

    /**
     * Forward refresh request to auth service
     */
    public function refresh(Request $request): JsonResponse
    {
        try {
            $token = $request->bearerToken();
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->post($this->authServiceUrl . '/api/auth/refresh');

            return response()->json($response->json(), $response->status());

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Auth service unavailable',
                'error' => $e->getMessage(),
            ], 503);
        }
    }
} 