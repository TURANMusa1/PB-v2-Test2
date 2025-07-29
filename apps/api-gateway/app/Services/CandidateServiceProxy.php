<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

class CandidateServiceProxy
{
    private string $candidateServiceUrl;

    public function __construct()
    {
        $this->candidateServiceUrl = env('CANDIDATE_SERVICE_URL', 'http://localhost:8003');
    }

    private function getHeaders(Request $request = null): array
    {
        $headers = [];
        if ($request && $request->bearerToken()) {
            $headers['Authorization'] = 'Bearer ' . $request->bearerToken();
        }
        return $headers;
    }

    public function getCandidates(array $filters = [], Request $request = null): Response
    {
        try {
            return Http::withHeaders($this->getHeaders($request))
                ->timeout(10)
                ->get($this->candidateServiceUrl . '/api/candidates', $filters);
        } catch (\Exception $e) {
            return Http::fake()->get('', [])->throw($e);
        }
    }

    public function getCandidate(int $id, Request $request = null): Response
    {
        try {
            return Http::withHeaders($this->getHeaders($request))
                ->timeout(10)
                ->get($this->candidateServiceUrl . "/api/candidates/{$id}");
        } catch (\Exception $e) {
            return Http::fake()->get('', [])->throw($e);
        }
    }

    public function createCandidate(array $data, Request $request = null): Response
    {
        try {
            return Http::withHeaders($this->getHeaders($request))
                ->timeout(10)
                ->post($this->candidateServiceUrl . '/api/candidates', $data);
        } catch (\Exception $e) {
            return Http::fake()->post('', [])->throw($e);
        }
    }

    public function updateCandidate(int $id, array $data, Request $request = null): Response
    {
        try {
            return Http::withHeaders($this->getHeaders($request))
                ->timeout(10)
                ->put($this->candidateServiceUrl . "/api/candidates/{$id}", $data);
        } catch (\Exception $e) {
            return Http::fake()->put('', [])->throw($e);
        }
    }

    public function deleteCandidate(int $id, Request $request = null): Response
    {
        try {
            return Http::withHeaders($this->getHeaders($request))
                ->timeout(10)
                ->delete($this->candidateServiceUrl . "/api/candidates/{$id}");
        } catch (\Exception $e) {
            return Http::fake()->delete('', [])->throw($e);
        }
    }

    public function getCandidateStats(Request $request = null): Response
    {
        try {
            return Http::withHeaders($this->getHeaders($request))
                ->timeout(10)
                ->get($this->candidateServiceUrl . '/api/candidates/stats');
        } catch (\Exception $e) {
            return Http::fake()->get('', [])->throw($e);
        }
    }

    public function updateCandidateStatus(int $id, string $status, Request $request = null): Response
    {
        try {
            return Http::withHeaders($this->getHeaders($request))
                ->timeout(10)
                ->patch($this->candidateServiceUrl . "/api/candidates/{$id}/status", [
                    'status' => $status
                ]);
        } catch (\Exception $e) {
            return Http::fake()->patch('', [])->throw($e);
        }
    }

    public function updateLastContact(int $id, Request $request = null): Response
    {
        try {
            return Http::withHeaders($this->getHeaders($request))
                ->timeout(10)
                ->patch($this->candidateServiceUrl . "/api/candidates/{$id}/contact");
        } catch (\Exception $e) {
            return Http::fake()->patch('', [])->throw($e);
        }
    }

    public function health(): Response
    {
        return Http::get($this->candidateServiceUrl . '/api/health');
    }
} 