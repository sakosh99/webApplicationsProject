<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Spatie\Health\Checks\Checks\CacheCheck;
use Spatie\Health\Checks\Checks\DatabaseCheck;
use Spatie\Health\Checks\Checks\DebugModeCheck;
use Spatie\Health\Checks\Checks\EnvironmentCheck;
use Spatie\Health\Checks\Checks\MeiliSearchCheck;
use Spatie\Health\Checks\Checks\OptimizedAppCheck;
use Spatie\Health\Checks\Checks\UsedDiskSpaceCheck;
use Spatie\Health\Facades\Health;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('path.public', function () {
            return base_path() . '/public_html';
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        DB::listen(function ($query) {
            $querySQL = $query->sql;
            if (str_contains($querySQL, 'insert') || str_contains($querySQL, 'update') || str_contains($querySQL, 'delete')) {
                File::append(
                    storage_path('logs/DB-LOG-' . Carbon::today()->toDateString() . '.log'),
                    '[' . Carbon::now()->format('Y-m-d H:i:s') . '] local.INFO: ' . $query->sql . ' {"bindings":[' . implode(', ', $query->bindings) . '],"time":' . $query->time . '}' . PHP_EOL
                );
            }
        });

        Health::checks([
            // UsedDiskSpaceCheck::new()
            //     ->warnWhenUsedSpaceIsAbovePercentage(70)
            //     ->failWhenUsedSpaceIsAbovePercentage(90),
            CacheCheck::new(),
            DatabaseCheck::new(),
            EnvironmentCheck::new(),
            // DebugModeCheck::new(),
            // OptimizedAppCheck::new(),
            // MeiliSearchCheck::new()->url("http://localhost:8090/api/file/group-files/5"),
        ]);
    }
}
