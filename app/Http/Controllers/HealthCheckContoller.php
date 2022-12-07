<?php

namespace App\Http\Controllers;

use App\Services\HealthService;
use Illuminate\Http\Request;



class HealthCheckContoller extends Controller
{
    public function __construct(private HealthService $healthService)
    {
    }

    public function getHealthReports(Request $request)
    {
        $reports = $this->healthService->HealthReports($request);

        return $this->successResponse(
            $reports,
            'Reports fetched successfully',
        );
    }
}
