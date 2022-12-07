<?php

namespace App\Http\Controllers;

use App\Models\Health;
use App\Repository\HealthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Spatie\Health\Commands\RunHealthChecksCommand;


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
