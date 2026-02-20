<?php

namespace App\Http\Controllers;

/**
 * @group Health
 * Health check endpoints.
 */
class HealthController extends Controller
{

    /**
     * Check API health.
     *
     * @unauthenticated
     */
    public function __invoke()
    {
        return response()->json(['status' => 'ok']);
    }
}
