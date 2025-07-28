<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

class CandidateServiceProxy
{
    private string $candidateServiceUrl;

    public function __construct()
    {
        $this->candidateServiceUrl = env('CANDIDATE_SERVICE_URL', 'http://localhost:8003');
    }

    public function getCandidates(array $filters = []): Response
    {
        try {
            return Http::timeout(10)->get($this->candidateServiceUrl . '/api/candidates', $filters);
        } catch (\Exception $e) {
            return Http::fake()->get('', [])->throw($e);
        }
    }

    public function getCandidate(int $id): Response
    {
        try {
            return Http::timeout(10)->get($this->candidateServiceUrl . "/api/candidates/{$id}");
        } catch (\Exception $e) {
            return Http::fake()->get('', [])->throw($e);
        }
    }

    public function createCandidate(array $data): Response
    {
        try {
            return Http::timeout(10)->post($this->candidateServiceUrl . '/api/candidates', $data);
        } catch (\Exception $e) {
            return Http::fake()->post('', [])->throw($e);
        }
    }

    public function updateCandidate(int $id, array $data): Response
    {
        try {
            return Http::timeout(10)->put($this->candidateServiceUrl . "/api/candidates/{$id}", $data);
        } catch (\Exception $e) {
            return Http::fake()->put('', [])->throw($e);
        }
    }

    public function deleteCandidate(int $id): Response
    {
        try {
            return Http::timeout(10)->delete($this->candidateServiceUrl . "/api/candidates/{$id}");
        } catch (\Exception $e) {
            return Http::fake()->delete('', [])->throw($e);
        }
    }

    public function getCandidateStats(): Response
    {
        try {
            return Http::timeout(10)->get($this->candidateServiceUrl . '/api/candidates/stats');
        } catch (\Exception $e) {
            return Http::fake()->get('', [])->throw($e);
        }
    }

    public function updateCandidateStatus(int $id, string $status): Response
    {
        try {
            return Http::timeout(10)->patch($this->candidateServiceUrl . "/api/candidates/{$id}/status", [
                'status' => $status
            ]);
        } catch (\Exception $e) {
            return Http::fake()->patch('', [])->throw($e);
        }
    }

    public function updateLastContact(int $id): Response
    {
        try {
            return Http::timeout(10)->patch($this->candidateServiceUrl . "/api/candidates/{$id}/contact");
        } catch (\Exception $e) {
            return Http::fake()->patch('', [])->throw($e);
        }
    }

    public function health(): Response
    {
        return Http::get($this->candidateServiceUrl . '/api/health');
    }
} 