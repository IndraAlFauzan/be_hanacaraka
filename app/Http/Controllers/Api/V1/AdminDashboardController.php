<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function __construct(
        protected DashboardService $dashboardService
    ) {}

    /**
     * Get dashboard statistics
     */
    public function dashboard(): JsonResponse
    {
        $statistics = $this->dashboardService->getStatistics();

        return response()->json([
            'success' => true,
            'data' => $statistics,
        ]);
    }

    /**
     * Get paginated user list with filters
     */
    public function users(Request $request): JsonResponse
    {
        $filters = [
            'role' => $request->input('role', 'pemain'),
            'search' => $request->input('search'),
        ];

        $perPage = $request->input('per_page', 20);
        $users = $this->dashboardService->getUsers($filters, $perPage);

        return response()->json([
            'success' => true,
            'data' => $users,
        ]);
    }
}
