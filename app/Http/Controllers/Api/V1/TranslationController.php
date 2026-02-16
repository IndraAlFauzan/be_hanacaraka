<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\TranslateRequest;
use App\Services\TranslationService;
use Illuminate\Http\JsonResponse;

class TranslationController extends Controller
{
    public function __construct(
        protected TranslationService $translationService
    ) {}

    /**
     * Translate Latin text to Javanese script
     */
    public function latinToJavanese(TranslateRequest $request): JsonResponse
    {
        $input = $request->validated()['text'];
        $output = $this->translationService->translateToJavanese($input);

        return response()->json([
            'success' => true,
            'data' => [
                'input' => $input,
                'output' => $output,
            ],
        ]);
    }

    /**
     * Translate Javanese script to Latin text
     */
    public function javaneseToLatin(TranslateRequest $request): JsonResponse
    {
        $input = $request->validated()['text'];
        $output = $this->translationService->translateToLatin($input);

        return response()->json([
            'success' => true,
            'data' => [
                'input' => $input,
                'output' => $output,
            ],
        ]);
    }
}
