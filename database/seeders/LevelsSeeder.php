<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Level;

class LevelsSeeder extends Seeder
{
    public function run(): void
    {
        $levels = [
            ['level_number' => 1, 'title' => 'Pengenalan Aksara Jawa', 'description' => 'Level dasar untuk mengenal huruf-huruf Aksara Jawa', 'xp_required' => 0],
            ['level_number' => 2, 'title' => 'Aksara Vokal', 'description' => 'Mempelajari aksara vokal dalam Aksara Jawa', 'xp_required' => 150],
            ['level_number' => 3, 'title' => 'Aksara Konsonan Dasar', 'description' => 'Menguasai konsonan dasar Aksara Jawa', 'xp_required' => 350],
            ['level_number' => 4, 'title' => 'Sandhangan', 'description' => 'Mempelajari sandhangan dalam Aksara Jawa', 'xp_required' => 600],
            ['level_number' => 5, 'title' => 'Aksara Pasangan', 'description' => 'Menguasai aksara pasangan', 'xp_required' => 900],
            ['level_number' => 6, 'title' => 'Aksara Murda', 'description' => 'Mempelajari aksara murda atau huruf kapital Jawa', 'xp_required' => 1250],
            ['level_number' => 7, 'title' => 'Aksara Angka', 'description' => 'Menguasai penulisan angka dalam Aksara Jawa', 'xp_required' => 1650],
            ['level_number' => 8, 'title' => 'Mahir Aksara Jawa', 'description' => 'Level mahir untuk menguasai semua aspek Aksara Jawa', 'xp_required' => 2100],
        ];

        foreach ($levels as $level) {
            Level::create($level);
        }
    }
}
