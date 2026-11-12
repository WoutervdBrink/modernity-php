<?php

namespace App\Providers;

use App\Contracts\Git\ApplicationCommitSource;
use App\Contracts\Git\RepositoryStorage;
use App\Contracts\Modernity\ModernityAnalyzer;
use App\Services\Git\LocalRepositoryStorage;
use App\Services\Modernity\Php\PhpModernityAnalyzer;
use Illuminate\Support\ServiceProvider;
use Override;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Register any application services.
     */
    #[Override]
    public function register(): void
    {
        $this->app->bind(
            RepositoryStorage::class,
            LocalRepositoryStorage::class
        );

        $this->app->bind(
            ApplicationCommitSource::class,
            LocalRepositoryStorage::class
        );

        $this->app->bind(
            ModernityAnalyzer::class,
            PhpModernityAnalyzer::class
        );
    }
}
