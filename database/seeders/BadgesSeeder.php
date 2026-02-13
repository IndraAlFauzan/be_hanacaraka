<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Badge;

class BadgesSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [
            // XP Milestone badges
            ['name' => 'Pemula', 'description' => 'Mencapai 50 XP', 'criteria_type' => 'xp_milestone', 'criteria_value' => 50],
            ['name' => 'Pembelajar', 'description' => 'Mencapai 100 XP', 'criteria_type' => 'xp_milestone', 'criteria_value' => 100],
            ['name' => 'Gigih', 'description' => 'Mencapai 250 XP', 'criteria_type' => 'xp_milestone', 'criteria_value' => 250],
            ['name' => 'Berdedikasi', 'description' => 'Mencapai 500 XP', 'criteria_type' => 'xp_milestone', 'criteria_value' => 500],
            ['name' => 'Ahli', 'description' => 'Mencapai 1000 XP', 'criteria_type' => 'xp_milestone', 'criteria_value' => 1000],
            ['name' => 'Master', 'description' => 'Mencapai 2000 XP', 'criteria_type' => 'xp_milestone', 'criteria_value' => 2000],

            // Streak badges
            ['name' => 'Konsisten 3 Hari', 'description' => 'Belajar 3 hari berturut-turut', 'criteria_type' => 'streak', 'criteria_value' => 3],
            ['name' => 'Konsisten 7 Hari', 'description' => 'Belajar 7 hari berturut-turut', 'criteria_type' => 'streak', 'criteria_value' => 7],
            ['name' => 'Konsisten 14 Hari', 'description' => 'Belajar 14 hari berturut-turut', 'criteria_type' => 'streak', 'criteria_value' => 14],
            ['name' => 'Konsisten 30 Hari', 'description' => 'Belajar 30 hari berturut-turut', 'criteria_type' => 'streak', 'criteria_value' => 30],

            // Level completion badges
            ['name' => 'Level 1 Selesai', 'description' => 'Menyelesaikan Level 1', 'criteria_type' => 'level_complete', 'criteria_value' => 1],
            ['name' => 'Level 2 Selesai', 'description' => 'Menyelesaikan Level 2', 'criteria_type' => 'level_complete', 'criteria_value' => 2],
            ['name' => 'Level 3 Selesai', 'description' => 'Menyelesaikan Level 3', 'criteria_type' => 'level_complete', 'criteria_value' => 3],
            ['name' => 'Level 4 Selesai', 'description' => 'Menyelesaikan Level 4', 'criteria_type' => 'level_complete', 'criteria_value' => 4],
            ['name' => 'Level 5 Selesai', 'description' => 'Menyelesaikan Level 5', 'criteria_type' => 'level_complete', 'criteria_value' => 5],
            ['name' => 'Level 6 Selesai', 'description' => 'Menyelesaikan Level 6', 'criteria_type' => 'level_complete', 'criteria_value' => 6],
            ['name' => 'Level 7 Selesai', 'description' => 'Menyelesaikan Level 7', 'criteria_type' => 'level_complete', 'criteria_value' => 7],
            ['name' => 'Level 8 Selesai', 'description' => 'Menyelesaikan Level 8', 'criteria_type' => 'level_complete', 'criteria_value' => 8],
        ];

        foreach ($badges as $badge) {
            Badge::create($badge);
        }
    }
}
