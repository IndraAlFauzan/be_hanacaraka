<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DrawingEvaluationService
{
    /**
     * Evaluate drawing similarity using ML service.
     *
     * @param string $referenceImageUrl
     * @param string $userDrawingUrl
     * @return float
     * @throws \Exception
     */
    public function evaluateDrawing(string $referenceImageUrl, string $userDrawingUrl): float
    {
        $mlServiceUrl = env('ML_SERVICE_URL', 'http://localhost:5000');

        try {
            $response = Http::timeout(30)->post("{$mlServiceUrl}/evaluate", [
                'reference_image_url' => $referenceImageUrl,
                'user_drawing_url' => $userDrawingUrl,
            ]);

            if ($response->failed()) {
                Log::error('ML Service failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                throw new \Exception('ML service unavailable. Please try again later.');
            }

            $similarityScore = $response->json('similarity_score');

            if ($similarityScore === null) {
                throw new \Exception('Invalid response from ML service');
            }

            return (float) $similarityScore;
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('ML Service connection error', ['error' => $e->getMessage()]);
            throw new \Exception('Cannot connect to ML service. Please try again later.');
        } catch (\Exception $e) {
            Log::error('Drawing evaluation error', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Check if similarity score passes the threshold.
     *
     * @param float $score
     * @param float $threshold
     * @return bool
     */
    public function isPassed(float $score, float $threshold = 70.0): bool
    {
        return $score >= $threshold;
    }
}
