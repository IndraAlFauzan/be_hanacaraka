<?php

namespace App\Services;

class TranslationService
{
    // Mapping Latin to Javanese script
    private array $latinToJavanese = [
        'a' => 'ꦄ',
        'A' => 'ꦄ',
        'i' => 'ꦆ',
        'I' => 'ꦆ',
        'u' => 'ꦈ',
        'U' => 'ꦈ',
        'e' => 'ꦌ',
        'E' => 'ꦌ',
        'o' => 'ꦎ',
        'O' => 'ꦎ',
        'ka' => 'ꦏ',
        'ga' => 'ꦒ',
        'nga' => 'ꦔ',
        'ca' => 'ꦕ',
        'ja' => 'ꦗ',
        'nya' => 'ꦚ',
        'ta' => 'ꦠ',
        'da' => 'ꦢ',
        'na' => 'ꦤ',
        'pa' => 'ꦥ',
        'ba' => 'ꦧ',
        'ma' => 'ꦩ',
        'ya' => 'ꦪ',
        'ra' => 'ꦫ',
        'la' => 'ꦭ',
        'wa' => 'ꦮ',
        'sa' => 'ꦱ',
        'ha' => 'ꦲ',
        ' ' => ' ',
    ];

    /**
     * Translate Latin text to Javanese script
     */
    public function translateToJavanese(string $text): string
    {
        $text = strtolower($text);
        $result = '';
        $i = 0;

        while ($i < strlen($text)) {
            $matched = false;

            // Try to match 3-letter combinations first (nga, nya)
            if ($i + 3 <= strlen($text)) {
                $three = substr($text, $i, 3);
                if (isset($this->latinToJavanese[$three])) {
                    $result .= $this->latinToJavanese[$three];
                    $i += 3;
                    $matched = true;
                }
            }

            // Try to match 2-letter combinations
            if (!$matched && $i + 2 <= strlen($text)) {
                $two = substr($text, $i, 2);
                if (isset($this->latinToJavanese[$two])) {
                    $result .= $this->latinToJavanese[$two];
                    $i += 2;
                    $matched = true;
                }
            }

            // Single letter
            if (!$matched) {
                $one = substr($text, $i, 1);
                $result .= $this->latinToJavanese[$one] ?? $one;
                $i++;
            }
        }

        return $result;
    }

    /**
     * Translate Javanese script to Latin text
     */
    public function translateToLatin(string $text): string
    {
        $javaneseToLatin = array_flip($this->latinToJavanese);
        $result = '';

        $chars = preg_split('//u', $text, -1, PREG_SPLIT_NO_EMPTY);

        foreach ($chars as $char) {
            $result .= $javaneseToLatin[$char] ?? $char;
        }

        return $result;
    }
}
