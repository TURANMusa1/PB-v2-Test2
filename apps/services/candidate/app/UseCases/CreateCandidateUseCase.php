<?php

namespace App\UseCases;

use App\Models\Candidate;
use App\Services\CandidateService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CreateCandidateUseCase
{
    public function __construct(
        private CandidateService $candidateService
    ) {}

    public function execute(array $data): array
    {
        l('CreateCandidateUseCase: Starting candidate creation', 'info');
        
        try {
            // Validate input data
            $this->validateInput($data);
            
            // Create candidate
            $candidate = $this->candidateService->createCandidate($data);
            
            l('CreateCandidateUseCase: Candidate created successfully', 'info', [
                'candidate_id' => $candidate->id,
                'email' => $candidate->email
            ]);
            
            return [
                'success' => true,
                'message' => 'Candidate created successfully',
                'data' => $candidate
            ];
            
        } catch (ValidationException $e) {
            l('CreateCandidateUseCase: Validation failed', 'error', [
                'errors' => $e->errors()
            ]);
            
            return [
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ];
            
        } catch (\Exception $e) {
            l('CreateCandidateUseCase: Unexpected error', 'error', [
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'message' => 'Failed to create candidate',
                'error' => $e->getMessage()
            ];
        }
    }

    private function validateInput(array $data): void
    {
        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
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
            'skills' => 'nullable|array',
            'education' => 'nullable|array',
            'experience' => 'nullable|array',
            'company_id' => 'nullable|integer',
            'created_by' => 'nullable|integer',
        ];

        $validator = Validator::make($data, $rules);
        
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
} 