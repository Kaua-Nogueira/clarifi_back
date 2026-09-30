<?php

namespace App\Providers;

use App\Contracts\ContentRepositoryInterface;
use App\Repositories\EloquentContentRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ContentRepositoryInterface::class, EloquentContentRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
