<?php

namespace App\UseCases;

use App\Services\CandidateService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class GetCandidatesUseCase
{
    public function __construct(
        private CandidateService $candidateService
    ) {}

    public function execute(array $filters = []): array
    {
        l('GetCandidatesUseCase: Starting candidate retrieval', 'info', $filters);
        
        try {
            $result = [];
            
            // Handle different filter types
            if (isset($filters['search'])) {
                $candidates = $this->candidateService->searchCandidates($filters['search']);
                $result = [
                    'success' => true,
                    'message' => 'Candidates found',
                    'data' => $candidates,
                    'total' => $candidates->count()
                ];
            } elseif (isset($filters['company_id'])) {
                $candidates = $this->candidateService->getCandidatesByCompany($filters['company_id']);
                $result = [
                    'success' => true,
                    'message' => 'Candidates found for company',
                    'data' => $candidates,
                    'total' => $candidates->count()
                ];
            } elseif (isset($filters['status'])) {
                $candidates = $this->candidateService->getCandidatesByStatus($filters['status']);
                $result = [
                    'success' => true,
                    'message' => 'Candidates found by status',
                    'data' => $candidates,
                    'total' => $candidates->count()
                ];
            } elseif (isset($filters['experience_level'])) {
                $candidates = $this->candidateService->getCandidatesByExperienceLevel($filters['experience_level']);
                $result = [
                    'success' => true,
                    'message' => 'Candidates found by experience level',
                    'data' => $candidates,
                    'total' => $candidates->count()
                ];
            } elseif (isset($filters['paginate'])) {
                $perPage = $filters['per_page'] ?? 15;
                $candidates = $this->candidateService->paginateCandidates($perPage);
                $result = [
                    'success' => true,
                    'message' => 'Candidates paginated',
                    'data' => $candidates->items(),
                    'pagination' => [
                        'current_page' => $candidates->currentPage(),
                        'last_page' => $candidates->lastPage(),
                        'per_page' => $candidates->perPage(),
                        'total' => $candidates->total(),
                        'from' => $candidates->firstItem(),
                        'to' => $candidates->lastItem(),
                    ]
                ];
            } else {
                // Get all candidates
                $candidates = $this->candidateService->getAllCandidates();
                $result = [
                    'success' => true,
                    'message' => 'All candidates retrieved',
                    'data' => $candidates,
                    'total' => $candidates->count()
                ];
            }
            
            l('GetCandidatesUseCase: Candidates retrieved successfully', 'info', [
                'total' => $result['total'] ?? count($result['data'])
            ]);
            
            return $result;
            
        } catch (\Exception $e) {
            l('GetCandidatesUseCase: Error retrieving candidates', 'error', [
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'message' => 'Failed to retrieve candidates',
                'error' => $e->getMessage()
            ];
        }
    }
} 