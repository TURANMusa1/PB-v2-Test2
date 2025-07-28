<?php

namespace App\Repositories;

use App\Models\Candidate;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CandidateRepository
{
    public function __construct(
        private Candidate $model
    ) {}

    public function findById(int $id): ?Candidate
    {
        return $this->model->find($id);
    }

    public function findByEmail(string $email): ?Candidate
    {
        return $this->model->where('email', $email)->first();
    }

    public function create(array $data): Candidate
    {
        return $this->model->create($data);
    }

    public function update(Candidate $candidate, array $data): bool
    {
        return $candidate->update($data);
    }

    public function delete(Candidate $candidate): bool
    {
        return $candidate->delete();
    }

    public function getAll(): Collection
    {
        return $this->model->all();
    }

    public function getActive(): Collection
    {
        return $this->model->where('status', 'active')->get();
    }

    public function getByCompany(int $companyId): Collection
    {
        return $this->model->where('company_id', $companyId)->get();
    }

    public function getByStatus(string $status): Collection
    {
        return $this->model->where('status', $status)->get();
    }

    public function getByExperienceLevel(string $level): Collection
    {
        return $this->model->where('experience_level', $level)->get();
    }

    public function search(string $query): Collection
    {
        return $this->model->where(function ($q) use ($query) {
            $q->where('first_name', 'like', "%{$query}%")
              ->orWhere('last_name', 'like', "%{$query}%")
              ->orWhere('email', 'like', "%{$query}%")
              ->orWhere('current_position', 'like', "%{$query}%")
              ->orWhere('current_company', 'like', "%{$query}%")
              ->orWhere('summary', 'like', "%{$query}%");
        })->get();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->paginate($perPage);
    }

    public function paginateByCompany(int $companyId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->where('company_id', $companyId)->paginate($perPage);
    }

    public function getBySkills(array $skills): Collection
    {
        return $this->model->whereJsonContains('skills', $skills)->get();
    }

    public function getRecent(int $days = 30): Collection
    {
        return $this->model->where('created_at', '>=', now()->subDays($days))->get();
    }

    public function updateLastContact(Candidate $candidate): bool
    {
        return $candidate->update(['last_contact_at' => now()]);
    }

    public function getStats(): array
    {
        return [
            'total' => $this->model->count(),
            'active' => $this->model->where('status', 'active')->count(),
            'hired' => $this->model->where('status', 'hired')->count(),
            'rejected' => $this->model->where('status', 'rejected')->count(),
            'with_cv' => $this->model->whereNotNull('cv_file_path')->count(),
        ];
    }
} 