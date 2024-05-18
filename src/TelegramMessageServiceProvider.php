<?php

namespace Itpekov\TelegramMessage;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class TelegramMessageServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(TelegramMessage::class, function ($app) {
            return new TelegramMessage(
                http: $app->make(Http::class),
                botToken: config('telegram.bot_token'),
                chatId: config('telegram.chat_id'),
            );
        });

        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'telegram');
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {

            // Publish configs
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('telegram.php'),
            ], 'config');

            // Publish views
            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/telegram-message'),
            ], 'views');
        }

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'telegram-message');
    }
}
