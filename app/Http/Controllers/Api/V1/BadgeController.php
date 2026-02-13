<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\BadgeResource;
use App\Services\BadgeService;
use Illuminate\Http\JsonResponse;

class BadgeController extends Controller
{
    public function __construct(
        protected BadgeService $badgeService
    ) {}

    /**
     * Get all active badges
     */
    public function index(): JsonResponse
    {
        $badges = $this->badgeService->getAllActiveBadges();

        return response()->json([
            'success' => true,
            'data' => BadgeResource::collection($badges),
        ]);
    }

    /**
     * Get current authenticated user's earned badges
     */
    public function myBadges(): JsonResponse
    {
        $badgeData = $this->badgeService->getUserBadges(auth()->id());

        return response()->json([
            'success' => true,
            'data' => $badgeData,
        ]);
    }

    /**
     * Get user's badges by ID (Admin only)
     */
    public function userBadges(int $userId): JsonResponse
    {
        $badgeData = $this->badgeService->getUserBadges($userId);

        return response()->json([
            'success' => true,
            'data' => $badgeData,
        ]);
    }
}
