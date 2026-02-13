<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class HealthController extends Controller
{
    /**
     * API health check endpoint
     */
    public function check()
    {
        $health = [
            'status' => 'ok',
            'timestamp' => now()->toIso8601String(),
            'database' => 'disconnected',
            'redis' => 'disconnected'
        ];

        // Check database connection
        try {
            DB::connection()->getPdo();
            $health['database'] = 'connected';
        } catch (\Exception $e) {
            $health['status'] = 'degraded';
            $health['database_error'] = $e->getMessage();
        }

        // Check Redis connection
        try {
            Redis::ping();
            $health['redis'] = 'connected';
        } catch (\Exception $e) {
            $health['status'] = 'degraded';
            $health['redis_error'] = $e->getMessage();
        }

        $statusCode = $health['status'] === 'ok' ? 200 : 503;

        return response()->json($health, $statusCode);
    }
}
