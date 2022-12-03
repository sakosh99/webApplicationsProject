<?php

namespace App\Http\Controllers;

use App\Models\Health;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Spatie\Health\Commands\RunHealthChecksCommand;


class HealthCheckContoller extends Controller
{

    public function getHealthReports(Request $request)
    {
        $reports = Health::all();

        if ($request->has('fresh')) {
            Artisan::call(RunHealthChecksCommand::class);

            $lastBatch = Health::latest('created_at')->first();
            $reports = Health::whereBatch($lastBatch->batch)->get();
        }

        return $this->successResponse(
            $reports,
            'Reports fetched successfully',
        );
    }
}
