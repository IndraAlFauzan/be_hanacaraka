<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Stage;
use App\Models\UserProgress;
use Illuminate\Http\Request;

class DashboardWebController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::where('role', 'pemain')->count(),
            'active_users_today' => User::where('role', 'pemain')
                ->whereDate('last_activity_date', today())
                ->count(),
            'total_stages' => Stage::count(),
            'total_completed_stages' => UserProgress::where('status', 'completed')->count(),
            'avg_completion_rate' => 0,
            'top_5_users' => User::where('role', 'pemain')
                ->orderBy('total_xp', 'desc')
                ->limit(5)
                ->get(),
            'latest_registrations' => User::where('role', 'pemain')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
        ];

        $totalUsers = $stats['total_users'];
        $totalStages = $stats['total_stages'];
        if ($totalUsers > 0 && $totalStages > 0) {
            $stats['avg_completion_rate'] = ($stats['total_completed_stages'] / ($totalUsers * $totalStages)) * 100;
        }

        return view('admin.dashboard', compact('stats'));
    }
}
