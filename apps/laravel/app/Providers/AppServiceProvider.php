<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        DB::prohibitDestructiveCommands(app()->isProduction());

        if (app()->isProduction() || env('VERCEL_ENV')) {
            URL::forceScheme('https');
        }
    }
}
