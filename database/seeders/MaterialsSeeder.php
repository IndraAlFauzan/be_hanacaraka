<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Stage;
use App\Models\Material;

class MaterialsSeeder extends Seeder
{
    /**
     * Aksara Jawa characters with their Latin names
     */
    private array $aksaraJawa = [
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

    public function run(): void
    {
        $aksaraArray = array_values($this->aksaraJawa);
        $aksaraKeys = array_keys($this->aksaraJawa);

        // Get Level 1 stages for basic aksara (first 20 characters)
        $level1Stages = Stage::whereHas('level', function ($query) {
            $query->where('level_number', 1);
        })->orderBy('stage_number')->get();

        foreach ($level1Stages as $index => $stage) {
            if ($index >= count($aksaraArray)) {
                break;
            }

            $aksara = $aksaraKeys[$index];
            $info = $aksaraArray[$index];

            // Create material for this stage
            Material::create([
                'stage_id' => $stage->id,
                'title' => "Mengenal Aksara {$info['name']}",
                'content_text' => "Pelajari cara menulis dan membaca aksara {$info['name']} ({$aksara})",
                'content_markdown' => $this->generateMarkdownContent($aksara, $info),
                'image_url' => "/storage/aksara/{$info['name']}.png",
                'order_index' => 1,
            ]);

            // Update stage title to be more descriptive
            $stage->update([
                'title' => "Aksara {$info['name']}",
            ]);
        }

        $this->command->info('Materials seeded successfully for Level 1!');
    }

    /**
     * Generate markdown content for aksara learning material
     */
    private function generateMarkdownContent(string $aksara, array $info): string
    {
        return <<<MARKDOWN
# Aksara {$info['name']}

## Bentuk Aksara

<div style="text-align: center; font-size: 72px; margin: 20px 0;">
{$aksara}
</div>

## Deskripsi

{$info['description']}

## Cara Penulisan

1. Perhatikan bentuk dasar aksara **{$info['name']}**
2. Mulai dari titik awal yang ditentukan
3. Ikuti arah garis dengan urutan yang benar
4. Latih berulang kali untuk membentuk kebiasaan

## Tips Mengingat

- Aksara **{$info['name']}** dibaca seperti suku kata "{$info['name']}" dalam bahasa Indonesia
- Perhatikan ciri khas bentuknya yang membedakan dari aksara lain
- Praktikkan menulis dengan mengikuti contoh

## Latihan

Setelah memahami bentuk aksara, lanjutkan ke tahap evaluasi untuk menguji kemampuanmu menulis aksara **{$info['name']}**.
MARKDOWN;
    }
}
