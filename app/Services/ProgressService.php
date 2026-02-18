<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserProgress;
use App\Models\Stage;
use App\Models\Level;
use App\Models\UserBadge;
use App\Models\Badge;
use App\Models\QuizResult;
use App\Models\ChallengeResult;
use Illuminate\Support\Facades\DB;

class ProgressService
{
    /**
     * Check if a stage is unlocked for a user.
     *
     * @param int $userId
     * @param int $stageId
     * @return bool
     */
    public function isStageUnlocked(int $userId, int $stageId): bool
    {
        $stage = Stage::with('level')->findOrFail($stageId);

        // Stage pertama di level pertama selalu unlocked
        if ($stage->level->level_number === 1 && $stage->stage_number === 1) {
            return true;
        }

        // Cek apakah level ini sudah unlocked
        $user = User::findOrFail($userId);
        if ($user->total_xp < $stage->level->xp_required) {
            return false;
        }

        // Jika ini stage pertama di level ini, cek apakah level sebelumnya sudah selesai
        if ($stage->stage_number === 1) {
            return true; // Level sudah unlocked berdasarkan XP
        }

        // Cari stage sebelumnya (level_id sama, stage_number - 1)
        $previousStage = Stage::where('level_id', $stage->level_id)
            ->where('stage_number', $stage->stage_number - 1)
            ->first();

        if (!$previousStage) {
            return false;
        }

        // Cek apakah stage sebelumnya sudah completed
        $progress = UserProgress::where('user_id', $userId)
            ->where('stage_id', $previousStage->id)
            ->where('status', 'completed')
            ->exists();

        return $progress;
    }

    /**
     * Complete a stage and unlock the next one.
     *
     * @param int $userId
     * @param int $stageId
     * @return array
     */
    public function completeStage(int $userId, int $stageId): array
    {
        DB::beginTransaction();
        try {
            // Update current stage progress
            UserProgress::updateOrCreate(
                ['user_id' => $userId, 'stage_id' => $stageId],
                [
                    'status' => 'completed',
                    'completed_at' => now(),
                    'last_accessed_at' => now()
                ]
            );

            // Find next stage (same level, stage_number + 1)
            $currentStage = Stage::findOrFail($stageId);
            $nextStage = Stage::where('level_id', $currentStage->level_id)
                ->where('stage_number', $currentStage->stage_number + 1)
                ->first();

            $result = ['next_stage_unlocked' => null];

            if ($nextStage) {
                // Unlock next stage
                UserProgress::updateOrCreate(
                    ['user_id' => $userId, 'stage_id' => $nextStage->id],
                    ['status' => 'unlocked', 'last_accessed_at' => now()]
                );
                $result['next_stage_unlocked'] = [
                    'id' => $nextStage->id,
                    'title' => $nextStage->title,
                    'stage_number' => $nextStage->stage_number,
                ];
            } else {
                // Tidak ada stage berikutnya, cek level up
                $levelUnlock = $this->checkLevelUnlock($userId);
                if ($levelUnlock) {
                    $result['next_stage_unlocked'] = $levelUnlock;
                }
            }

            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Check and unlock next level if requirements met.
     *
     * @param int $userId
     * @return array|null
     */
    private function checkLevelUnlock(int $userId): ?array
    {
        $user = User::findOrFail($userId);

        // Ambil level berikutnya yang belum unlocked
        $nextLevel = Level::where('xp_required', '<=', $user->total_xp)
            ->orderBy('level_number')
            ->get()
            ->last();

        if (!$nextLevel) {
            return null;
        }

        // Check if there's a level after current
        $higherLevel = Level::where('level_number', '>', $nextLevel->level_number)
            ->where('xp_required', '<=', $user->total_xp)
            ->first();

        if ($higherLevel) {
            $nextLevel = $higherLevel;
        }

        // Unlock stage pertama di level baru
        $firstStage = Stage::where('level_id', $nextLevel->id)
            ->where('stage_number', 1)
            ->first();

        if ($firstStage) {
            // Check if not already unlocked
            $existing = UserProgress::where('user_id', $userId)
                ->where('stage_id', $firstStage->id)
                ->first();

            if (!$existing || $existing->status === 'locked') {
                UserProgress::updateOrCreate(
                    ['user_id' => $userId, 'stage_id' => $firstStage->id],
                    ['status' => 'unlocked', 'last_accessed_at' => now()]
                );

                return [
                    'id' => $firstStage->id,
                    'title' => $firstStage->title,
                    'level_number' => $nextLevel->level_number,
                    'stage_number' => $firstStage->stage_number,
                ];
            }
        }

        return null;
    }

    /**
     * Get user progress summary.
     *
     * @param int $userId
     * @return array
     */
    public function getUserProgressSummary(int $userId): array
    {
        $user = User::findOrFail($userId);
        $totalStages = Stage::where('is_active', true)->count();
        $completedStages = UserProgress::where('user_id', $userId)
            ->where('status', 'completed')
            ->count();

        $progressPercentage = $totalStages > 0 ? ($completedStages / $totalStages) * 100 : 0;

        return [
            'user_id' => $userId,
            'total_xp' => $user->total_xp,
            'current_level' => $user->current_level,
            'total_completed_stages' => $completedStages,
            'total_stages' => $totalStages,
            'completion_percentage' => round($progressPercentage, 1),
            'current_streak' => $user->streak_count,
            'last_activity_date' => $user->last_activity_date,
        ];
    }

    /**
     * Get detailed user progress summary with extended information.
     *
     * @param int $userId
     * @return array
     */
    public function getDetailedProgressSummary(int $userId): array
    {
        $user = User::with(['badges.badge'])->findOrFail($userId);

        // Basic progress info
        $totalStages = Stage::where('is_active', true)->count();
        $completedStages = UserProgress::where('user_id', $userId)
            ->where('status', 'completed')
            ->count();
        $inProgressStages = UserProgress::where('user_id', $userId)
            ->where('status', 'in_progress')
            ->count();
        $unlockedStages = UserProgress::where('user_id', $userId)
            ->where('status', 'unlocked')
            ->count();

        $progressPercentage = $totalStages > 0 ? ($completedStages / $totalStages) * 100 : 0;

        // Level progress
        $currentLevel = Level::where('level_number', $user->current_level)->first();
        $nextLevel = Level::where('level_number', $user->current_level + 1)->first();
        $xpToNextLevel = $nextLevel ? ($nextLevel->xp_required - $user->total_xp) : 0;
        $currentLevelProgress = 0;

        if ($currentLevel && $nextLevel) {
            $xpInCurrentLevel = $user->total_xp - $currentLevel->xp_required;
            $xpNeededForLevel = $nextLevel->xp_required - $currentLevel->xp_required;
            $currentLevelProgress = $xpNeededForLevel > 0 ? ($xpInCurrentLevel / $xpNeededForLevel) * 100 : 0;
        }

        // Quiz statistics
        $quizResults = QuizResult::where('user_id', $userId)->get();
        $totalQuizzes = $quizResults->count();
        $passedQuizzes = $quizResults->where('passed', true)->count();
        $failedQuizzes = $totalQuizzes - $passedQuizzes;
        $averageQuizScore = $totalQuizzes > 0 ? $quizResults->avg('score') : 0;
        $totalQuizXp = $quizResults->sum('xp_earned');

        // Drawing/Writing evaluations statistics
        $challengeResults = ChallengeResult::where('user_id', $userId)->get();
        $totalEvaluations = $challengeResults->count();
        $passedEvaluations = $challengeResults->where('is_passed', true)->count();
        $averageEvaluationScore = $totalEvaluations > 0 ? $challengeResults->avg('similarity_score') : 0;

        // XP from evaluations is included in stage completion, set to 0 to avoid double counting
        $totalEvaluationXp = 0;

        // Badge information
        $totalBadges = Badge::where('is_active', true)->count();
        $earnedBadges = $user->badges->count();
        $badgeProgress = $totalBadges > 0 ? ($earnedBadges / $totalBadges) * 100 : 0;

        $badges = $user->badges->map(function ($badge) {
            return [
                'badge_id' => $badge->id,
                'name' => $badge->name,
                'description' => $badge->description,
                'icon_url' => $badge->icon_path ? asset('storage/' . $badge->icon_path) : null,
                'xp_reward' => $badge->xp_reward,
                'earned_at' => $badge->pivot->earned_at,
            ];
        });

        // XP breakdown
        $stageXp = UserProgress::where('user_id', $userId)
            ->where('status', 'completed')
            ->join('stages', 'user_progress.stage_id', '=', 'stages.id')
            ->sum('stages.xp_reward');

        $badgeXp = $user->badges->sum('xp_reward');

        $xpBreakdown = [
            'from_stages' => $stageXp,
            'from_quizzes' => $totalQuizXp,
            'from_evaluations' => $totalEvaluationXp,
            'from_badges' => $badgeXp,
            'total' => $user->total_xp,
        ];

        // Recent activities
        $recentActivities = [];

        // Recent completed stages
        $recentStages = UserProgress::with('stage.level')
            ->where('user_id', $userId)
            ->where('status', 'completed')
            ->whereNotNull('completed_at')
            ->orderBy('completed_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($progress) {
                return [
                    'type' => 'stage_completed',
                    'title' => $progress->stage->title,
                    'level' => 'Level ' . $progress->stage->level->level_number,
                    'xp_earned' => $progress->stage->xp_reward,
                    'timestamp' => $progress->completed_at,
                ];
            });

        // Recent quiz results
        $recentQuizzes = QuizResult::with('quiz')
            ->where('user_id', $userId)
            ->orderBy('completed_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($result) {
                return [
                    'type' => 'quiz_completed',
                    'title' => $result->quiz->title ?? 'Quiz',
                    'score' => $result->score,
                    'passed' => $result->passed,
                    'xp_earned' => $result->xp_earned,
                    'timestamp' => $result->completed_at,
                ];
            });

        // Recent badges earned
        $recentBadges = $user->badges()
            ->orderBy('user_badges.earned_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($badge) {
                return [
                    'type' => 'badge_earned',
                    'title' => $badge->name,
                    'description' => $badge->description,
                    'xp_earned' => $badge->xp_reward,
                    'timestamp' => $badge->pivot->earned_at,
                ];
            });

        $recentActivities = $recentStages
            ->merge($recentQuizzes)
            ->merge($recentBadges)
            ->sortByDesc('timestamp')
            ->take(10)
            ->values();

        // Learning streak details
        $streakInfo = [
            'current_streak' => $user->streak_count,
            'last_activity' => $user->last_activity_date,
            'is_active_today' => $user->last_activity_date
                ? $user->last_activity_date->isToday()
                : false,
        ];

        // Time estimates (based on completed activities)
        $estimatedTimeSpent = [
            'stages_minutes' => $completedStages * 15, // Estimate 15 min per stage
            'quizzes_minutes' => $totalQuizzes * 10, // Estimate 10 min per quiz
            'evaluations_minutes' => $totalEvaluations * 20, // Estimate 20 min per evaluation
            'total_minutes' => ($completedStages * 15) + ($totalQuizzes * 10) + ($totalEvaluations * 20),
            'total_hours' => round((($completedStages * 15) + ($totalQuizzes * 10) + ($totalEvaluations * 20)) / 60, 1),
        ];

        return [
            // Basic info
            'user_id' => $userId,
            'name' => $user->name,
            'email' => $user->email,

            // XP and level
            'xp' => [
                'total' => $user->total_xp,
                'current_level' => $user->current_level,
                'current_level_name' => $currentLevel?->title,
                'next_level' => $nextLevel?->level_number,
                'next_level_name' => $nextLevel?->title,
                'xp_to_next_level' => max(0, $xpToNextLevel),
                'current_level_progress_percentage' => round(max(0, $currentLevelProgress), 1),
                'breakdown' => $xpBreakdown,
            ],

            // Overall progress
            'progress' => [
                'total_stages' => $totalStages,
                'completed_stages' => $completedStages,
                'in_progress_stages' => $inProgressStages,
                'unlocked_stages' => $unlockedStages,
                'locked_stages' => $totalStages - ($completedStages + $inProgressStages + $unlockedStages),
                'completion_percentage' => round($progressPercentage, 1),
            ],

            // Quiz statistics
            'quiz_stats' => [
                'total_quizzes' => $totalQuizzes,
                'passed' => $passedQuizzes,
                'failed' => $failedQuizzes,
                'pass_rate' => $totalQuizzes > 0 ? round(($passedQuizzes / $totalQuizzes) * 100, 1) : 0,
                'average_score' => round($averageQuizScore, 1),
                'total_xp_earned' => $totalQuizXp,
            ],

            // Evaluation statistics
            'evaluation_stats' => [
                'total_evaluations' => $totalEvaluations,
                'completed' => $passedEvaluations,
                'average_score' => round($averageEvaluationScore, 1),
                'total_xp_earned' => $totalEvaluationXp,
            ],

            // Badge information
            'badges' => [
                'total_available' => $totalBadges,
                'earned' => $earnedBadges,
                'remaining' => $totalBadges - $earnedBadges,
                'completion_percentage' => round($badgeProgress, 1),
                'list' => $badges,
            ],

            // Learning streak
            'streak' => $streakInfo,

            // Time spent
            'time_spent' => $estimatedTimeSpent,

            // Recent activities
            'recent_activities' => $recentActivities,
        ];
    }

    /**
     * Get detailed progress per level with stages.
     *
     * @param int $userId
     * @return array
     */
    public function getDetailedLevelProgress(int $userId): array
    {
        $user = User::findOrFail($userId);
        $levels = Level::with(['stages' => function ($query) {
            $query->where('is_active', true)->orderBy('stage_number');
        }])->where('is_active', true)->orderBy('level_number')->get();

        $userProgress = UserProgress::where('user_id', $userId)
            ->get()
            ->keyBy('stage_id');

        $levelsProgress = [];

        foreach ($levels as $level) {
            $totalStages = $level->stages->count();
            $completedStages = 0;
            $stagesDetail = [];

            foreach ($level->stages as $stage) {
                $progress = $userProgress->get($stage->id);
                $status = $progress ? $progress->status : 'locked';

                // Stage pertama di level pertama selalu unlocked
                if ($level->level_number === 1 && $stage->stage_number === 1 && !$progress) {
                    $status = 'unlocked';
                }

                if ($status === 'completed') {
                    $completedStages++;
                }

                $stagesDetail[] = [
                    'stage_id' => $stage->id,
                    'stage_number' => $stage->stage_number,
                    'title' => $stage->title,
                    'xp_reward' => $stage->xp_reward,
                    'evaluation_type' => $stage->evaluation_type,
                    'status' => $status,
                    'completed_at' => $progress?->completed_at,
                ];
            }

            $levelPercentage = $totalStages > 0 ? ($completedStages / $totalStages) * 100 : 0;
            $isUnlocked = $user->total_xp >= $level->xp_required;

            $levelsProgress[] = [
                'level_id' => $level->id,
                'level_number' => $level->level_number,
                'title' => $level->title,
                'xp_required' => $level->xp_required,
                'is_unlocked' => $isUnlocked,
                'total_stages' => $totalStages,
                'completed_stages' => $completedStages,
                'completion_percentage' => round($levelPercentage, 1),
                'stages' => $stagesDetail,
            ];
        }

        return $levelsProgress;
    }

    /**
     * Get progress for a specific level.
     *
     * @param int $userId
     * @param int $levelId
     * @return array|null
     */
    public function getLevelProgress(int $userId, int $levelId): ?array
    {
        $user = User::findOrFail($userId);
        $level = Level::with(['stages' => function ($query) {
            $query->where('is_active', true)->orderBy('stage_number');
        }])->findOrFail($levelId);

        $userProgress = UserProgress::where('user_id', $userId)
            ->whereIn('stage_id', $level->stages->pluck('id'))
            ->get()
            ->keyBy('stage_id');

        $totalStages = $level->stages->count();
        $completedStages = 0;
        $stagesDetail = [];

        foreach ($level->stages as $stage) {
            $progress = $userProgress->get($stage->id);
            $status = $progress ? $progress->status : 'locked';

            // Stage pertama di level pertama selalu unlocked
            if ($level->level_number === 1 && $stage->stage_number === 1 && !$progress) {
                $status = 'unlocked';
            }

            if ($status === 'completed') {
                $completedStages++;
            }

            $stagesDetail[] = [
                'stage_id' => $stage->id,
                'stage_number' => $stage->stage_number,
                'title' => $stage->title,
                'xp_reward' => $stage->xp_reward,
                'evaluation_type' => $stage->evaluation_type,
                'status' => $status,
                'completed_at' => $progress?->completed_at,
            ];
        }

        $levelPercentage = $totalStages > 0 ? ($completedStages / $totalStages) * 100 : 0;
        $isUnlocked = $user->total_xp >= $level->xp_required;

        return [
            'level_id' => $level->id,
            'level_number' => $level->level_number,
            'title' => $level->title,
            'description' => $level->description,
            'xp_required' => $level->xp_required,
            'is_unlocked' => $isUnlocked,
            'total_stages' => $totalStages,
            'completed_stages' => $completedStages,
            'completion_percentage' => round($levelPercentage, 1),
            'stages' => $stagesDetail,
        ];
    }
}
