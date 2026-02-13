<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserWebController extends Controller
{
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

        // Hitung progress
        $completedStages = $user->progress()->where('status', 'completed')->count();
        $completedQuizzes = $user->quizResults()->where('is_passed', true)->count();
        $completedEvaluations = $user->challengeResults()->where('is_passed', true)->count();

        // Badge yang diraih
        $userBadges = $user->badges()->with('badge')->orderBy('earned_at', 'desc')->get();

        // Aktivitas terakhir
        $recentProgress = $user->progress()
            ->with(['stage.level'])
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.users.show', compact(
            'user',
            'completedStages',
            'completedQuizzes',
            'completedEvaluations',
            'userBadges',
            'recentProgress'
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
