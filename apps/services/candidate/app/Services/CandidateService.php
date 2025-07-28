<?php

namespace App\Services;

use App\Models\Candidate;
use App\Repositories\CandidateRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CandidateService
{
    public function __construct(
        private CandidateRepository $repository
    ) {}

    public function getAllCandidates(): Collection
    {
        l('Getting all candidates', 'info');
        return $this->repository->getAll();
    }

    public function getActiveCandidates(): Collection
    {
        l('Getting active candidates', 'info');
        return $this->repository->getActive();
    }

    public function getCandidateById(int $id): ?Candidate
    {
        l("Getting candidate by ID: {$id}", 'info');
        return $this->repository->findById($id);
    }

    public function createCandidate(array $data): Candidate
    {
        l('Creating new candidate', 'info', ['email' => $data['email'] ?? 'unknown']);
        
        $this->validateCandidateData($data);
        
        // Check if email already exists
        if ($this->repository->findByEmail($data['email'])) {
            throw new ValidationException(Validator::make([], []));
        }

        return $this->repository->create($data);
    }

    public function updateCandidate(int $id, array $data): ?Candidate
    {
        l("Updating candidate: {$id}", 'info');
        
        $candidate = $this->repository->findById($id);
        if (!$candidate) {
            return null;
        }

        $this->validateCandidateData($data, $id);
        
        $this->repository->update($candidate, $data);
        
        return $candidate->fresh();
    }

    public function deleteCandidate(int $id): bool
    {
        l("Deleting candidate: {$id}", 'info');
        
        $candidate = $this->repository->findById($id);
        if (!$candidate) {
            return false;
        }

        return $this->repository->delete($candidate);
    }

    public function searchCandidates(string $query): Collection
    {
        l("Searching candidates with query: {$query}", 'info');
        return $this->repository->search($query);
    }

    public function getCandidatesByCompany(int $companyId): Collection
    {
        l("Getting candidates for company: {$companyId}", 'info');
        return $this->repository->getByCompany($companyId);
    }

    public function getCandidatesByStatus(string $status): Collection
    {
        l("Getting candidates by status: {$status}", 'info');
        return $this->repository->getByStatus($status);
    }

    public function getCandidatesByExperienceLevel(string $level): Collection
    {
        l("Getting candidates by experience level: {$level}", 'info');
        return $this->repository->getByExperienceLevel($level);
    }

    public function paginateCandidates(int $perPage = 15): LengthAwarePaginator
    {
        l("Paginating candidates with per page: {$perPage}", 'info');
        return $this->repository->paginate($perPage);
    }

    public function getCandidateStats(): array
    {
        l('Getting candidate statistics', 'info');
        return $this->repository->getStats();
    }

    public function updateCandidateStatus(int $id, string $status): bool
    {
        l("Updating candidate status: {$id} -> {$status}", 'info');
        
        $candidate = $this->repository->findById($id);
        if (!$candidate) {
            return false;
        }

        return $this->repository->update($candidate, ['status' => $status]);
    }

    public function updateLastContact(int $id): bool
    {
        l("Updating last contact for candidate: {$id}", 'info');
        
        $candidate = $this->repository->findById($id);
        if (!$candidate) {
            return false;
        }

        return $this->repository->updateLastContact($candidate);
    }

    private function validateCandidateData(array $data, ?int $excludeId = null): void
    {
        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255' . ($excludeId ? "|unique:candidates,email,{$excludeId}" : '|unique:candidates'),
            'phone' => 'nullable|string|max:20',
            'summary' => 'nullable|string',
            'experience_level' => 'nullable|in:entry,mid,senior,expert',
            'current_position' => 'nullable|string|max:255',
            'current_company' => 'nullable|string|max:255',
            'expected_salary' => 'nullable|numeric|min:0',
            'location' => 'nullable|string|max:255',
            'linkedin_url' => 'nullable|url',
            'github_url' => 'nullable|url',
            'portfolio_url' => 'nullable|url',
            'cv_file_path' => 'nullable|string',
            'skills' => 'nullable|array',
            'education' => 'nullable|array',
            'experience' => 'nullable|array',
            'status' => 'nullable|in:active,inactive,hired,rejected',
            'company_id' => 'nullable|integer',
            'created_by' => 'nullable|integer',
        ];

        $validator = Validator::make($data, $rules);
        
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
} 