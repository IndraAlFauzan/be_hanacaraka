<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class HealthController extends Controller
{
    /**
     * API health check endpoint
     */
    public function check(): JsonResponse
    {
        $health = [
            'status' => 'ok',
            'timestamp' => now()->toIso8601String(),
            'database' => 'disconnected',
            'cache' => 'unknown',
        ];

        // Check database connection
        try {
            DB::connection()->getPdo();
            $health['database'] = 'connected';
        } catch (\Exception $e) {
            $health['status'] = 'degraded';
            $health['database_error'] = $e->getMessage();
        }

        // Check cache driver
        try {
            $cacheDriver = config('cache.default');
            $health['cache_driver'] = $cacheDriver;

            // Test cache read/write
            $testKey = 'health_check_' . time();
            Cache::put($testKey, 'ok', 10);
            $result = Cache::get($testKey);
            Cache::forget($testKey);

            $health['cache'] = $result === 'ok' ? 'working' : 'not working';
        } catch (\Exception $e) {
            $health['cache'] = 'error';
            $health['cache_error'] = $e->getMessage();
        }

        $statusCode = $health['status'] === 'ok' ? 200 : 503;

        return response()->json($health, $statusCode);
    }
}
