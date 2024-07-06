<?php

namespace Itpekov\TelegramMessage;

use Illuminate\Support\ServiceProvider;

class TelegramMessageServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(TelegramMessage::class, function () {
            return new TelegramMessage(
                botToken: config('telegram.bot_token') ?? '',
                chatId: config('telegram.chat_id') ?? '',
            );
        });

        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'telegram');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            // Publish configs
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('telegram.php'),
            ], 'config');

            // Publish views
            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/telegram-message'),
            ], 'views');
        }

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'telegram-message');
    }
}
