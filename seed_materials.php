<?php

// Quick script to seed materials
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Stage;
use App\Models\Material;

$aksaraJawa = [
    'ꦲ' => ['name' => 'Ha', 'description' => 'Aksara dasar pertama dalam urutan Hanacaraka'],
    'ꦤ' => ['name' => 'Na', 'description' => 'Aksara kedua dalam urutan Hanacaraka'],
    'ꦕ' => ['name' => 'Ca', 'description' => 'Aksara ketiga dalam urutan Hanacaraka'],
    'ꦫ' => ['name' => 'Ra', 'description' => 'Aksara keempat dalam urutan Hanacaraka'],
    'ꦏ' => ['name' => 'Ka', 'description' => 'Aksara kelima dalam urutan Hanacaraka'],
    'ꦢ' => ['name' => 'Da', 'description' => 'Aksara keenam dalam urutan Hanacaraka'],
    'ꦠ' => ['name' => 'Ta', 'description' => 'Aksara ketujuh dalam urutan Hanacaraka'],
    'ꦱ' => ['name' => 'Sa', 'description' => 'Aksara kedelapan dalam urutan Hanacaraka'],
    'ꦮ' => ['name' => 'Wa', 'description' => 'Aksara kesembilan dalam urutan Hanacaraka'],
    'ꦭ' => ['name' => 'La', 'description' => 'Aksara kesepuluh dalam urutan Hanacaraka'],
    'ꦥ' => ['name' => 'Pa', 'description' => 'Aksara kesebelas dalam urutan Hanacaraka'],
    'ꦝ' => ['name' => 'Dha', 'description' => 'Aksara kedua belas dalam urutan Hanacaraka'],
    'ꦗ' => ['name' => 'Ja', 'description' => 'Aksara ketiga belas dalam urutan Hanacaraka'],
    'ꦪ' => ['name' => 'Ya', 'description' => 'Aksara keempat belas dalam urutan Hanacaraka'],
    'ꦚ' => ['name' => 'Nya', 'description' => 'Aksara kelima belas dalam urutan Hanacaraka'],
    'ꦩ' => ['name' => 'Ma', 'description' => 'Aksara keenam belas dalam urutan Hanacaraka'],
    'ꦒ' => ['name' => 'Ga', 'description' => 'Aksara ketujuh belas dalam urutan Hanacaraka'],
    'ꦧ' => ['name' => 'Ba', 'description' => 'Aksara kedelapan belas dalam urutan Hanacaraka'],
    'ꦛ' => ['name' => 'Tha', 'description' => 'Aksara kesembilan belas dalam urutan Hanacaraka'],
    'ꦔ' => ['name' => 'Nga', 'description' => 'Aksara kedua puluh (terakhir) dalam urutan Hanacaraka'],
];

$aksaraArray = array_values($aksaraJawa);
$aksaraKeys = array_keys($aksaraJawa);

// Get Level 1 stages
$level1Stages = Stage::whereHas('level', function ($query) {
    $query->where('level_number', 1);
})->orderBy('stage_number')->get();

echo "Found " . $level1Stages->count() . " stages in Level 1\n";

$count = 0;
foreach ($level1Stages as $index => $stage) {
    if ($index >= count($aksaraArray)) {
        break;
    }

    $aksara = $aksaraKeys[$index];
    $info = $aksaraArray[$index];

    // Check if material already exists
    if (Material::where('stage_id', $stage->id)->exists()) {
        echo "Material for stage {$stage->id} already exists, skipping...\n";
        continue;
    }

    // Create material
    Material::create([
        'stage_id' => $stage->id,
        'title' => "Mengenal Aksara {$info['name']}",
        'content_text' => "Pelajari cara menulis dan membaca aksara {$info['name']} ({$aksara})",
        'content_markdown' => "# Aksara {$info['name']}\n\n## Bentuk Aksara\n\n{$aksara}\n\n## Deskripsi\n\n{$info['description']}",
        'image_url' => "/storage/aksara/{$info['name']}.png",
        'order_index' => 1,
    ]);

    // Update stage title
    $stage->update([
        'title' => "Aksara {$info['name']}",
    ]);

    $count++;
    echo "Created material for Stage {$stage->id}: Aksara {$info['name']}\n";
}

echo "\nDone! Created {$count} materials.\n";
echo "Total materials in database: " . Material::count() . "\n";
