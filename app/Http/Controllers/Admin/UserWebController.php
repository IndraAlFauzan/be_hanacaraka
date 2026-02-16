<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ProgressService;
use Illuminate\Http\Request;

class UserWebController extends Controller
{
    public function __construct(
        protected ProgressService $progressService
    ) {}

    public function index(Request $request)
    {
        $query = User::query()->where('role', 'pemain');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->withCount([
            'progress as completed_stages' => function ($q) {
                $q->where('status', 'completed');
            }
        ])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);

        // Get detailed progress using ProgressService
        $progressSummary = $this->progressService->getUserProgressSummary((int) $id);
        $levelsProgress = $this->progressService->getDetailedLevelProgress((int) $id);

        // Hitung progress
        $completedStages = $progressSummary['total_completed_stages'];
        $totalStages = $progressSummary['total_stages'];
        $completionPercentage = $progressSummary['completion_percentage'];
        $completedQuizzes = $user->quizResults()->where('is_passed', true)->count();
        $completedEvaluations = $user->challengeResults()->where('is_passed', true)->count();

        // Badge yang diraih - badges() already returns Badge models with pivot
        $userBadges = $user->badges()->orderByPivot('earned_at', 'desc')->get();

        // Aktivitas terakhir
        $recentProgress = $user->progress()
            ->with(['stage.level'])
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.users.show', compact(
            'user',
            'completedStages',
            'totalStages',
            'completionPercentage',
            'completedQuizzes',
            'completedEvaluations',
            'userBadges',
            'recentProgress',
            'levelsProgress',
            'progressSummary'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
