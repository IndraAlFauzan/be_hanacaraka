<?php

namespace App\Services;

use App\Models\Stage;
use App\Models\User;
use App\Models\UserProgress;

class DashboardService
{
    /**
     * Get dashboard statistics
     */
    public function getStatistics(): array
    {
        $totalUsers = User::where('role', 'pemain')->count();
        $totalStages = Stage::count();
        $totalCompletedStages = UserProgress::where('status', 'completed')->count();

        $avgCompletionRate = 0;
        if ($totalUsers > 0 && $totalStages > 0) {
            $avgCompletionRate = ($totalCompletedStages / ($totalUsers * $totalStages)) * 100;
        }

        return [
            'total_users' => $totalUsers,
            'active_users_today' => $this->getActiveUsersToday(),
            'total_stages' => $totalStages,
            'total_completed_stages' => $totalCompletedStages,
            'avg_completion_rate' => round($avgCompletionRate, 2),
            'top_5_users' => $this->getTopUsers(5),
            'latest_registrations' => $this->getLatestRegistrations(5),
            'weekly_registrations' => $this->getWeeklyRegistrations(),
        ];
    }

    /**
     * Get active users count for today
     */
    public function getActiveUsersToday(): int
    {
        return User::where('role', 'pemain')
            ->whereDate('last_activity_date', today())
            ->count();
    }

    /**
     * Get top users by XP
     */
    public function getTopUsers(int $limit = 5)
    {
        return User::where('role', 'pemain')
            ->orderBy('total_xp', 'desc')
            ->limit($limit)
            ->get(['id', 'name', 'email', 'total_xp', 'current_level', 'avatar_url']);
    }

    /**
     * Get latest registrations
     */
    public function getLatestRegistrations(int $limit = 5)
    {
        return User::where('role', 'pemain')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get(['id', 'name', 'email', 'created_at']);
    }

    /**
     * Get weekly registration trend
     */
    public function getWeeklyRegistrations()
    {
        return User::where('role', 'pemain')
            ->where('created_at', '>=', now()->subDays(7))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();
    }

    /**
     * Get paginated users with filters
     */
    public function getUsers(array $filters = [], int $perPage = 20)
    {
        $query = User::query();

        if (!empty($filters['role'])) {
            $query->where('role', $filters['role']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $query->withCount([
            'progress as completed_stages' => fn($q) => $q->where('status', 'completed'),
        ]);

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }
}
