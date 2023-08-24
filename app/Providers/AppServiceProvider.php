<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Services\CommentService;
use App\Services\AnswerService;
use App\Services\UserService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserService::class, function ($app) {
            return new UserService();
        });

        $this->app->bind(CommentService::class, function ($app) {
            return new CommentService($app->make(UserService::class));
        });

        $this->app->bind(AnswerService::class, function ($app) {
            return new AnswerService($app->make(UserService::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
