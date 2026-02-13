<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TranslationController extends Controller
{
    // Mapping Latin to Javanese script
    private $latinToJavanese = [
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
        ' ' => ' '
    ];

    /**
     * Translate Latin text to Javanese script
     */
    public function latinToJavanese(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'text' => 'required|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $input = $request->input('text');
        $output = $this->translateToJavanese($input);

        return response()->json([
            'success' => true,
            'data' => [
                'input' => $input,
                'output' => $output,
                'output_format' => 'javanese_script'
            ]
        ]);
    }

    /**
     * Translate Javanese script to Latin text
     */
    public function javaneseToLatin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'text' => 'required|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $input = $request->input('text');
        $output = $this->translateToLatin($input);

        return response()->json([
            'success' => true,
            'data' => [
                'input' => $input,
                'output' => $output,
                'output_format' => 'latin'
            ]
        ]);
    }

    /**
     * Simple transliteration logic (can be improved)
     */
    private function translateToJavanese($text)
    {
        $result = '';
        $text = strtolower($text);
        $length = mb_strlen($text);
        $i = 0;

        while ($i < $length) {
            $matched = false;

            // Try 3-char match (nga, nya)
            if ($i + 2 < $length) {
                $three = mb_substr($text, $i, 3);
                if (isset($this->latinToJavanese[$three])) {
                    $result .= $this->latinToJavanese[$three];
                    $i += 3;
                    $matched = true;
                }
            }

            // Try 2-char match (ka, ga, etc)
            if (!$matched && $i + 1 < $length) {
                $two = mb_substr($text, $i, 2);
                if (isset($this->latinToJavanese[$two])) {
                    $result .= $this->latinToJavanese[$two];
                    $i += 2;
                    $matched = true;
                }
            }

            // Try 1-char match
            if (!$matched) {
                $one = mb_substr($text, $i, 1);
                if (isset($this->latinToJavanese[$one])) {
                    $result .= $this->latinToJavanese[$one];
                } else {
                    $result .= $one; // Keep original if no match
                }
                $i++;
            }
        }

        return $result;
    }

    /**
     * Reverse translation (Javanese to Latin)
     */
    private function translateToLatin($text)
    {
        // Create reverse mapping
        $javaneseToLatin = array_flip($this->latinToJavanese);
        $result = '';
        $length = mb_strlen($text);

        for ($i = 0; $i < $length; $i++) {
            $char = mb_substr($text, $i, 1);
            if (isset($javaneseToLatin[$char])) {
                $result .= $javaneseToLatin[$char];
            } else {
                $result .= $char; // Keep original if no match
            }
        }

        return $result;
    }
}
