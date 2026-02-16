<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserProgress;
use App\Models\Stage;
use App\Models\Level;
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
