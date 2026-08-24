<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\MooraService;
\Illuminate\Pagination\Paginator::useBootstrap();
\Illuminate\Pagination\Paginator::useTailwind();

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(MooraService::class, function ($app) {
            return new MooraService();
        });
    }

    public function boot(): void
    {
        //
    }

}
