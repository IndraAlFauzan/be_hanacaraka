<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Level;
use App\Models\Stage;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\Evaluation;
use App\Models\Material;

class Level1QuizStagesSeeder extends Seeder
{
    /**
     * Aksara Jawa dasar (Ha Na Ca Ra Ka - 20 aksara)
     */
    private array $aksaraJawa = [
        ['aksara' => 'ꦲ', 'latin' => 'Ha', 'pelafalan' => 'ha'],
        ['aksara' => 'ꦤ', 'latin' => 'Na', 'pelafalan' => 'na'],
        ['aksara' => 'ꦕ', 'latin' => 'Ca', 'pelafalan' => 'ca'],
        ['aksara' => 'ꦫ', 'latin' => 'Ra', 'pelafalan' => 'ra'],
        ['aksara' => 'ꦏ', 'latin' => 'Ka', 'pelafalan' => 'ka'],
        ['aksara' => 'ꦢ', 'latin' => 'Da', 'pelafalan' => 'da'],
        ['aksara' => 'ꦠ', 'latin' => 'Ta', 'pelafalan' => 'ta'],
        ['aksara' => 'ꦱ', 'latin' => 'Sa', 'pelafalan' => 'sa'],
        ['aksara' => 'ꦮ', 'latin' => 'Wa', 'pelafalan' => 'wa'],
        ['aksara' => 'ꦭ', 'latin' => 'La', 'pelafalan' => 'la'],
        ['aksara' => 'ꦥ', 'latin' => 'Pa', 'pelafalan' => 'pa'],
        ['aksara' => 'ꦝ', 'latin' => 'Dha', 'pelafalan' => 'dha'],
        ['aksara' => 'ꦗ', 'latin' => 'Ja', 'pelafalan' => 'ja'],
        ['aksara' => 'ꦪ', 'latin' => 'Ya', 'pelafalan' => 'ya'],
        ['aksara' => 'ꦚ', 'latin' => 'Nya', 'pelafalan' => 'nya'],
        ['aksara' => 'ꦩ', 'latin' => 'Ma', 'pelafalan' => 'ma'],
        ['aksara' => 'ꦒ', 'latin' => 'Ga', 'pelafalan' => 'ga'],
        ['aksara' => 'ꦧ', 'latin' => 'Ba', 'pelafalan' => 'ba'],
        ['aksara' => 'ꦛ', 'latin' => 'Tha', 'pelafalan' => 'tha'],
        ['aksara' => 'ꦔ', 'latin' => 'Nga', 'pelafalan' => 'nga'],
    ];

    public function run(): void
    {
        // Get Level 1
        $level = Level::where('level_number', 1)->first();

        if (!$level) {
            $this->command->error('Level 1 tidak ditemukan! Jalankan LevelsSeeder terlebih dahulu.');
            return;
        }

        // Delete existing stages for Level 1 (optional - untuk fresh seed)
        Stage::where('level_id', $level->id)->delete();

        $this->command->info('Membuat stages untuk Level 1 dengan evaluation_type = quiz...');

        foreach ($this->aksaraJawa as $index => $aksara) {
            $stageNumber = $index + 1;

            // Create Stage with evaluation_type = 'quiz'
            $stage = Stage::create([
                'level_id' => $level->id,
                'stage_number' => $stageNumber,
                'title' => "Aksara {$aksara['latin']}",
                'xp_reward' => 15,
                'evaluation_type' => 'quiz',
                'is_active' => true,
            ]);

            // Create Material
            $this->createMaterial($stage, $aksara);

            // Create Evaluation (untuk referensi gambar aksara)
            $this->createEvaluation($stage, $aksara);

            // Create Quiz with Questions
            $this->createQuiz($stage, $aksara, $index);

            $this->command->info("  ✓ Stage {$stageNumber}: Aksara {$aksara['latin']}");
        }

        $this->command->info('Selesai! 20 stages Level 1 berhasil dibuat.');
    }

    private function createMaterial(Stage $stage, array $aksara): void
    {
        Material::create([
            'stage_id' => $stage->id,
            'title' => "Mengenal Aksara {$aksara['latin']}",
            'content_markdown' => $this->generateMaterialContent($aksara),
            'order_index' => 1,
        ]);
    }

    private function generateMaterialContent(array $aksara): string
    {
        return <<<EOT
# Aksara {$aksara['latin']}

## Bentuk Aksara
{$aksara['aksara']}

## Cara Membaca
Aksara ini dibaca **"{$aksara['pelafalan']}"**

## Contoh Penggunaan
- {$aksara['aksara']} + ꦤ = {$aksara['pelafalan']}na
- {$aksara['aksara']} + ꦏ = {$aksara['pelafalan']}ka

## Tips Mengingat
Perhatikan bentuk aksara dan hubungkan dengan bunyi "{$aksara['pelafalan']}". 
Latih menulis aksara ini berulang kali untuk mengingatnya.
EOT;
    }

    private function createEvaluation(Stage $stage, array $aksara): void
    {
        Evaluation::create([
            'stage_id' => $stage->id,
            'character_target' => $aksara['aksara'],
            'reference_image_url' => "/storage/aksara/{$aksara['latin']}.png",
            'min_similarity_score' => 70.00,
        ]);
    }

    private function createQuiz(Stage $stage, array $aksara, int $aksaraIndex): void
    {
        $quiz = Quiz::create([
            'stage_id' => $stage->id,
            'title' => "Quiz Aksara {$aksara['latin']}",
            'passing_score' => 60,
        ]);

        // Generate 5 questions per quiz
        $this->createQuizQuestions($quiz, $aksara, $aksaraIndex);
    }

    private function createQuizQuestions(Quiz $quiz, array $aksara, int $aksaraIndex): void
    {
        // Get other aksara for wrong options
        $otherAksara = collect($this->aksaraJawa)
            ->filter(fn($a, $i) => $i !== $aksaraIndex)
            ->values()
            ->all();

        // Question 1: Identify the aksara
        $wrongOptions1 = $this->getRandomWrongOptions($otherAksara, 3);
        QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question_text' => "Aksara Jawa di bawah ini dibaca apa?\n\n{$aksara['aksara']}",
            'option_a' => $aksara['latin'],
            'option_b' => $wrongOptions1[0]['latin'],
            'option_c' => $wrongOptions1[1]['latin'],
            'option_d' => $wrongOptions1[2]['latin'],
            'correct_answer' => 'a',
            'order_index' => 1,
        ]);

        // Question 2: Identify from latin
        $wrongOptions2 = $this->getRandomWrongOptions($otherAksara, 3);
        $options2 = $this->shuffleOptionsWithCorrect($aksara['aksara'], array_column($wrongOptions2, 'aksara'));
        QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question_text' => "Manakah aksara Jawa yang dibaca \"{$aksara['pelafalan']}\"?",
            'option_a' => $options2['options'][0],
            'option_b' => $options2['options'][1],
            'option_c' => $options2['options'][2],
            'option_d' => $options2['options'][3],
            'correct_answer' => $options2['correct'],
            'order_index' => 2,
        ]);

        // Question 3: True/False style
        $isTrue = rand(0, 1) === 1;
        if ($isTrue) {
            QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'question_text' => "Aksara {$aksara['aksara']} dibaca \"{$aksara['pelafalan']}\". Benar atau Salah?",
                'option_a' => 'Benar',
                'option_b' => 'Salah',
                'option_c' => '-',
                'option_d' => '-',
                'correct_answer' => 'a',
                'order_index' => 3,
            ]);
        } else {
            $wrongAksara = $wrongOptions1[0];
            QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'question_text' => "Aksara {$aksara['aksara']} dibaca \"{$wrongAksara['pelafalan']}\". Benar atau Salah?",
                'option_a' => 'Benar',
                'option_b' => 'Salah',
                'option_c' => '-',
                'option_d' => '-',
                'correct_answer' => 'b',
                'order_index' => 3,
            ]);
        }

        // Question 4: Complete the pattern
        $wrongOptions4 = $this->getRandomWrongOptions($otherAksara, 3);
        $options4 = $this->shuffleOptionsWithCorrect($aksara['aksara'], array_column($wrongOptions4, 'aksara'));
        QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question_text' => "Lengkapi: Aksara yang melambangkan bunyi \"{$aksara['pelafalan']}\" adalah...",
            'option_a' => $options4['options'][0],
            'option_b' => $options4['options'][1],
            'option_c' => $options4['options'][2],
            'option_d' => $options4['options'][3],
            'correct_answer' => $options4['correct'],
            'order_index' => 4,
        ]);

        // Question 5: Reverse identification
        $wrongOptions5 = $this->getRandomWrongOptions($otherAksara, 3);
        $options5 = $this->shuffleOptionsWithCorrect($aksara['latin'], array_column($wrongOptions5, 'latin'));
        QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question_text' => "Bagaimana cara membaca aksara berikut dalam huruf latin?\n\n{$aksara['aksara']}",
            'option_a' => $options5['options'][0],
            'option_b' => $options5['options'][1],
            'option_c' => $options5['options'][2],
            'option_d' => $options5['options'][3],
            'correct_answer' => $options5['correct'],
            'order_index' => 5,
        ]);
    }

    private function getRandomWrongOptions(array $otherAksara, int $count): array
    {
        $shuffled = $otherAksara;
        shuffle($shuffled);
        return array_slice($shuffled, 0, $count);
    }

    private function shuffleOptionsWithCorrect(string $correct, array $wrongOptions): array
    {
        $allOptions = array_merge([$correct], $wrongOptions);

        // Shuffle and track correct answer position
        $indexed = [];
        foreach ($allOptions as $i => $option) {
            $indexed[] = ['option' => $option, 'isCorrect' => $i === 0];
        }
        shuffle($indexed);

        $correctLetter = 'a';
        $options = [];
        foreach ($indexed as $i => $item) {
            $options[] = $item['option'];
            if ($item['isCorrect']) {
                $correctLetter = ['a', 'b', 'c', 'd'][$i];
            }
        }

        return [
            'options' => $options,
            'correct' => $correctLetter,
        ];
    }
}
