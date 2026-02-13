<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Stage;
use App\Models\UserProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    /**
     * Get dashboard statistics
     */
    public function dashboard()
    {
        // Total users
        $totalUsers = User::where('role', 'pemain')->count();

        // Active users today (users with activity today)
        $activeUsersToday = User::where('role', 'pemain')
            ->whereDate('last_activity_date', today())
            ->count();

        // Total stages
        $totalStages = Stage::count();

        // Total completed stages across all users
        $totalCompletedStages = UserProgress::where('status', 'completed')->count();

        // Average completion rate
        $avgCompletionRate = $totalUsers > 0
            ? ($totalCompletedStages / ($totalUsers * $totalStages)) * 100
            : 0;

        // Top 5 users by XP
        $topUsers = User::where('role', 'pemain')
            ->orderBy('total_xp', 'desc')
            ->limit(5)
            ->get(['id', 'name', 'email', 'total_xp', 'current_level', 'avatar_url']);

        // Latest 5 registrations
        $latestRegistrations = User::where('role', 'pemain')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get(['id', 'name', 'email', 'created_at']);

        // Weekly registration trend (last 7 days)
        $weeklyRegistrations = User::where('role', 'pemain')
            ->where('created_at', '>=', now()->subDays(7))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'total_users' => $totalUsers,
                'active_users_today' => $activeUsersToday,
                'total_stages' => $totalStages,
                'total_completed_stages' => $totalCompletedStages,
                'avg_completion_rate' => round($avgCompletionRate, 2),
                'top_5_users' => $topUsers,
                'latest_registrations' => $latestRegistrations,
                'weekly_registrations' => $weeklyRegistrations
            ]
        ]);
    }

    /**
     * Get paginated user list with filters
     */
    public function users(Request $request)
    {
        $perPage = $request->input('per_page', 20);
        $search = $request->input('search');
        $role = $request->input('role', 'pemain');

        $query = User::query();

        // Filter by role
        if ($role) {
            $query->where('role', $role);
        }

        // Search by name or email
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Add completed stages count
        $query->withCount([
            'progress as completed_stages' => function ($q) {
                $q->where('status', 'completed');
            }
        ]);

        $users = $query->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }
}
