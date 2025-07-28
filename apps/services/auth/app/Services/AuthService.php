<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function __construct(
        private UserRepository $userRepository
    ) {}

    /**
     * Login user and return token
     */
    public function login(string $email, string $password): array
    {
        $user = $this->userRepository->findByEmail($email);

        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if (!$user->isActive()) {
            throw ValidationException::withMessages([
                'email' => ['Your account is deactivated.'],
            ]);
        }

        // Revoke existing tokens
        $user->tokens()->delete();

        // Create new token
        $token = $user->createToken('auth-token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer',
        ];
    }

    /**
     * Register new user
     */
    public function register(array $data): array
    {
        // Check if user already exists
        $existingUser = $this->userRepository->findByEmail($data['email']);
        if ($existingUser) {
            throw ValidationException::withMessages([
                'email' => ['User with this email already exists.'],
            ]);
        }

        // Create user
        $user = $this->userRepository->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'] ?? 'hr',
            'company_id' => $data['company_id'] ?? null,
            'is_active' => true,
        ]);

        // Create token
        $token = $user->createToken('auth-token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer',
        ];
    }

    /**
     * Logout user
     */
    public function logout(User $user): bool
    {
        return $user->tokens()->delete();
    }

    /**
     * Get current user
     */
    public function getCurrentUser(User $user): User
    {
        return $user->load(['tokens']);
    }

    /**
     * Refresh token
     */
    public function refreshToken(User $user): array
    {
        // Revoke existing tokens
        $user->tokens()->delete();

        // Create new token
        $token = $user->createToken('auth-token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer',
        ];
    }
} 