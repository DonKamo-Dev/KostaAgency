<?php

namespace App\Providers;

use Anthropic\Client;
use Anthropic\ServiceContracts\MessagesContract;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Client::class, function () {
            $key = config('services.anthropic.key');
            if (empty($key)) {
                throw new \RuntimeException('ANTHROPIC_API_KEY is not configured.');
            }

            return new Client($key);
        });

        $this->app->singleton(MessagesContract::class, function ($app) {
            return $app->make(Client::class)->messages;
        });
    }

    public function boot(): void {}
}
