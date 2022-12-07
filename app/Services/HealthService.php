<?php

namespace App\Services;

use App\RepositoryInterface\HealthRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Spatie\Health\Commands\RunHealthChecksCommand;

class HealthService
{
    public function __construct(private HealthRepositoryInterface $healthRepository)
    {
    }
    public function healthReports(Request $request)
    {
        $reports = $this->healthRepository->all();
        if ($request->has('fresh')) {
            Artisan::call(RunHealthChecksCommand::class);
            $reports = $this->healthRepository->latest();
        }
        return $reports;
    }
}
