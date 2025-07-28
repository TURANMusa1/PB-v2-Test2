<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository
{
    public function __construct(
        private User $model
    ) {}

    /**
     * Find user by email
     */
    public function findByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }

    /**
     * Find user by ID
     */
    public function findById(int $id): ?User
    {
        return $this->model->find($id);
    }

    /**
     * Create new user
     */
    public function create(array $data): User
    {
        return $this->model->create($data);
    }

    /**
     * Update user
     */
    public function update(User $user, array $data): bool
    {
        return $user->update($data);
    }

    /**
     * Delete user
     */
    public function delete(User $user): bool
    {
        return $user->delete();
    }

    /**
     * Get all active users
     */
    public function getActiveUsers(): Collection
    {
        return $this->model->where('is_active', true)->get();
    }

    /**
     * Get users by role
     */
    public function getByRole(string $role): Collection
    {
        return $this->model->where('role', $role)->where('is_active', true)->get();
    }

    /**
     * Get users by company
     */
    public function getByCompany(int $companyId): Collection
    {
        return $this->model->where('company_id', $companyId)->where('is_active', true)->get();
    }

    /**
     * Paginate users
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->where('is_active', true)->paginate($perPage);
    }
} 