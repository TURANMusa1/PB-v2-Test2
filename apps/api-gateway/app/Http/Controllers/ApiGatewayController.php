<?php

namespace App\Http\Controllers;

use App\Services\AuthServiceProxy;
use App\Services\CandidateServiceProxy;
use App\Services\NotificationServiceProxy;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class ApiGatewayController extends Controller
{
    public function __construct(
        private AuthServiceProxy $authServiceProxy,
        private CandidateServiceProxy $candidateServiceProxy,
        private NotificationServiceProxy $notificationServiceProxy
    ) {}

    /**
     * Health check endpoint
     */
    public function health(): JsonResponse
    {
        return response()->json([
            'status' => 'ok',
            'service' => 'api-gateway',
            'timestamp' => now()->toISOString(),
                    'services' => [
            'auth' => $this->checkAuthService(),
            'candidate' => $this->checkCandidateService(),
            'notification' => $this->checkNotificationService(),
        ]
        ]);
    }

    /**
     * Auth routes
     */
    public function authLogin(Request $request): JsonResponse
    {
        $response = $this->authServiceProxy->login($request);
        return response()->json($response->json(), $response->status());
    }

    public function authRegister(Request $request): JsonResponse
    {
        $response = $this->authServiceProxy->register($request);
        return response()->json($response->json(), $response->status());
    }

    public function authLogout(Request $request): JsonResponse
    {
        $response = $this->authServiceProxy->logout($request);
        return response()->json($response->json(), $response->status());
    }

    public function authMe(Request $request): JsonResponse
    {
        $response = $this->authServiceProxy->me($request);
        return response()->json($response->json(), $response->status());
    }

    public function authRefresh(Request $request): JsonResponse
    {
        $response = $this->authServiceProxy->refresh($request);
        return response()->json($response->json(), $response->status());
    }

    /**
     * Candidate routes
     */
    public function candidateIndex(Request $request): JsonResponse
    {
        $response = $this->candidateServiceProxy->getCandidates($request->all());
        return response()->json($response->json(), $response->status());
    }

    public function candidateShow(int $id): JsonResponse
    {
        $response = $this->candidateServiceProxy->getCandidate($id);
        return response()->json($response->json(), $response->status());
    }

    public function candidateStore(Request $request): JsonResponse
    {
        $response = $this->candidateServiceProxy->createCandidate($request->all());
        return response()->json($response->json(), $response->status());
    }

    public function candidateUpdate(Request $request, int $id): JsonResponse
    {
        $response = $this->candidateServiceProxy->updateCandidate($id, $request->all());
        return response()->json($response->json(), $response->status());
    }

    public function candidateDestroy(int $id): JsonResponse
    {
        $response = $this->candidateServiceProxy->deleteCandidate($id);
        return response()->json($response->json(), $response->status());
    }

    public function candidateStats(): JsonResponse
    {
        $response = $this->candidateServiceProxy->getCandidateStats();
        return response()->json($response->json(), $response->status());
    }

    public function candidateUpdateStatus(Request $request, int $id): JsonResponse
    {
        $response = $this->candidateServiceProxy->updateCandidateStatus($id, $request->status);
        return response()->json($response->json(), $response->status());
    }

    public function candidateUpdateContact(int $id): JsonResponse
    {
        $response = $this->candidateServiceProxy->updateLastContact($id);
        return response()->json($response->json(), $response->status());
    }

    /**
     * Notification routes
     */
    public function notificationIndex(Request $request): JsonResponse
    {
        $response = $this->notificationServiceProxy->getNotifications($request->all());
        return response()->json($response->json(), $response->status());
    }

    public function notificationShow(int $id): JsonResponse
    {
        $response = $this->notificationServiceProxy->getNotification($id);
        return response()->json($response->json(), $response->status());
    }

    public function notificationStore(Request $request): JsonResponse
    {
        $response = $this->notificationServiceProxy->createNotification($request->all());
        return response()->json($response->json(), $response->status());
    }

    public function notificationUpdate(Request $request, int $id): JsonResponse
    {
        $response = $this->notificationServiceProxy->updateNotification($id, $request->all());
        return response()->json($response->json(), $response->status());
    }

    public function notificationDestroy(int $id): JsonResponse
    {
        $response = $this->notificationServiceProxy->deleteNotification($id);
        return response()->json($response->json(), $response->status());
    }

    public function notificationStats(): JsonResponse
    {
        $response = $this->notificationServiceProxy->getNotificationStats();
        return response()->json($response->json(), $response->status());
    }

    public function notificationRetry(int $id): JsonResponse
    {
        $response = $this->notificationServiceProxy->retryNotification($id);
        return response()->json($response->json(), $response->status());
    }

    public function notificationSend(int $id): JsonResponse
    {
        $response = $this->notificationServiceProxy->sendNotification($id);
        return response()->json($response->json(), $response->status());
    }

    public function notificationProcessScheduled(): JsonResponse
    {
        $response = $this->notificationServiceProxy->processScheduledNotifications();
        return response()->json($response->json(), $response->status());
    }

    public function notificationCleanup(Request $request): JsonResponse
    {
        $response = $this->notificationServiceProxy->cleanupNotifications($request->all());
        return response()->json($response->json(), $response->status());
    }

    /**
     * Check auth service health
     */
    private function checkAuthService(): array
    {
        try {
            $response = Http::timeout(5)->get(env('AUTH_SERVICE_URL', 'http://localhost:8002') . '/api/health');
            return [
                'status' => 'ok',
                'response_time' => $response->handlerStats()['total_time'] ?? null,
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Check candidate service health
     */
    private function checkCandidateService(): array
    {
        try {
            $response = Http::timeout(5)->get(env('CANDIDATE_SERVICE_URL', 'http://localhost:8003') . '/api/health');
            return [
                'status' => 'ok',
                'response_time' => $response->handlerStats()['total_time'] ?? null,
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Check notification service health
     */
    private function checkNotificationService(): array
    {
        try {
            $response = Http::timeout(5)->get(env('NOTIFICATION_SERVICE_URL', 'http://localhost:8004') . '/api/health');
            return [
                'status' => 'ok',
                'response_time' => $response->handlerStats()['total_time'] ?? null,
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'error' => $e->getMessage(),
            ];
        }
    }
} 