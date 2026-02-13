<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Level;
use App\Models\Stage;

class StagesSeeder extends Seeder
{
    public function run(): void
    {
        $levels = Level::all();

        foreach ($levels as $level) {
            // Create stages for each level
            // Level 1: 20 stages, Level 2: 18 stages, etc. to reach 135 total
            $stageCount = match ($level->level_number) {
                1 => 20,
                2 => 18,
                3 => 18,
                4 => 17,
                5 => 17,
                6 => 16,
                7 => 15,
                8 => 14,
                default => 15,
            };

            for ($i = 1; $i <= $stageCount; $i++) {
                Stage::create([
                    'level_id' => $level->id,
                    'stage_number' => $i,
                    'title' => "Stage {$i}: {$level->title}",
                    'xp_reward' => 10,
                    'is_active' => true,
                ]);
            }
        }
    }
}
