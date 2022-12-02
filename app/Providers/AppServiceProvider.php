<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Request as FacadesRequest;

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
    }
}
