<?php

namespace App\Http\Controllers;

use App\UseCases\CreateCandidateUseCase;
use App\UseCases\GetCandidatesUseCase;
use App\Services\CandidateService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CandidateController extends Controller
{
    public function __construct(
        private CreateCandidateUseCase $createCandidateUseCase,
        private GetCandidatesUseCase $getCandidatesUseCase,
        private CandidateService $candidateService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['search', 'company_id', 'status', 'experience_level', 'paginate', 'per_page']);
        
        $result = $this->getCandidatesUseCase->execute($filters);
        
        if ($result['success']) {
            return response()->json($result, 200);
        }
        
        return response()->json($result, 400);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->all();
        
        $result = $this->createCandidateUseCase->execute($data);
        
        if ($result['success']) {
            return response()->json($result, 201);
        }
        
        return response()->json($result, 400);
    }

    public function show(int $id): JsonResponse
    {
        $candidate = $this->candidateService->getCandidateById($id);
        
        if (!$candidate) {
            return response()->json([
                'success' => false,
                'message' => 'Candidate not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Candidate found',
            'data' => $candidate
        ], 200);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $data = $request->all();
        
        $candidate = $this->candidateService->updateCandidate($id, $data);
        
        if (!$candidate) {
            return response()->json([
                'success' => false,
                'message' => 'Candidate not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Candidate updated successfully',
            'data' => $candidate
        ], 200);
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->candidateService->deleteCandidate($id);
        
        if (!$deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Candidate not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Candidate deleted successfully'
        ], 200);
    }

    public function stats(): JsonResponse
    {
        $stats = $this->candidateService->getCandidateStats();
        
        return response()->json([
            'success' => true,
            'message' => 'Candidate statistics retrieved',
            'data' => $stats
        ], 200);
    }

    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:active,inactive,hired,rejected'
        ]);
        
        $updated = $this->candidateService->updateCandidateStatus($id, $request->status);
        
        if (!$updated) {
            return response()->json([
                'success' => false,
                'message' => 'Candidate not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Candidate status updated successfully'
        ], 200);
    }

    public function updateLastContact(int $id): JsonResponse
    {
        $updated = $this->candidateService->updateLastContact($id);
        
        if (!$updated) {
            return response()->json([
                'success' => false,
                'message' => 'Candidate not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Last contact updated successfully'
        ], 200);
    }
}
