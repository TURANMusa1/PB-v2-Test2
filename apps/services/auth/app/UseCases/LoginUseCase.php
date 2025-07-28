<?php

namespace App\UseCases;

use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class LoginUseCase
{
    public function __construct(
        private AuthService $authService
    ) {}

    /**
     * Execute login use case
     */
    public function execute(Request $request): array
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }

        // Attempt login
        $result = $this->authService->login(
            $request->input('email'),
            $request->input('password')
        );

        // Log successful login
        $this->log('debug', 'User logged in successfully', [
            'user_id' => $result['user']->id,
            'email' => $result['user']->email,
            'role' => $result['user']->role,
        ]);

        return $result;
    }

    /**
     * Log message using custom l() function
     */
    private function log(string $type, string $message, array $options = []): void
    {
        if (function_exists('l')) {
            l($message, $type, $options);
        } else {
            \Log::info($message, $options);
        }
    }
} 