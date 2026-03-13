<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Tag(
 *     name="Health",
 *     description="API Endpoints for health checking"
 * )
 */
class HealthController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/health",
     *     summary="Check application health",
     *     description="Returns the health status of the application including database connectivity",
     *     operationId="healthCheck",
     *     tags={"Health"},
     *     @OA\Response(
     *         response=200,
     *         description="System is healthy",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="ok"),
     *             @OA\Property(property="services", type="object",
     *                 @OA\Property(property="database", type="string", example="ok"),
     *                 @OA\Property(property="api", type="string", example="ok")
     *             ),
     *             @OA\Property(property="version", type="string", example="1.0.0")
     *         )
     *     ),
     *     @OA\Response(
     *         response=503,
     *         description="System is unhealthy",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="services", type="object",
     *                 @OA\Property(property="database", type="string", example="error"),
     *                 @OA\Property(property="api", type="string", example="ok")
     *             ),
     *             @OA\Property(property="version", type="string", example="1.0.0")
     *         )
     *     )
     * )
     */
    public function check()
    {
        $databaseStatus = 'ok';
        $status = 'ok';
        $statusCode = 200;

        // Check database connectivity
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            $databaseStatus = 'error';
            $status = 'error';
            $statusCode = 503; // Service Unavailable
        }

        $response = [
            'status' => $status,
            'services' => [
                'database' => $databaseStatus,
                'api' => 'ok'
            ],
            'version' => config('app.version', '1.0.0')
        ];

        return response()->json($response, $statusCode);
    }
}
