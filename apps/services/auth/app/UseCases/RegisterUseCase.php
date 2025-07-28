<?php

namespace App\UseCases;

use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class RegisterUseCase
{
    public function __construct(
        private AuthService $authService
    ) {}

    /**
     * Execute register use case
     */
    public function execute(Request $request): array
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
            'role' => 'sometimes|string|in:admin,hr,manager',
            'company_id' => 'sometimes|integer|exists:companies,id',
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }

        // Register user
        $result = $this->authService->register($request->all());

        // Log successful registration
        $this->log('debug', 'User registered successfully', [
            'user_id' => $result['user']->id,
            'email' => $result['user']->email,
            'role' => $result['user']->role,
            'company_id' => $result['user']->company_id,
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