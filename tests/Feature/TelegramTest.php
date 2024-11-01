<?php

namespace Itpekov\TelegramMessage\Tests\Feature;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Itpekov\TelegramMessage\Facades\Telegram;
use Itpekov\TelegramMessage\Tests\TestCase;

class TelegramTest extends TestCase
{
    /**
     * @test
     */
    public function throw_if_bot_token_not_set_in_env(): void
    {
        // Arrange
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Telegram bot token is required.');

        // Act
        Telegram::sendMessage('test');
    }

    /**
     * @test
     */
    public function throw_if_chat_id_not_set_in_env(): void
    {
        // Arrange
        Config::set('telegram.bot_token', 'test_token');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Telegram chat id is required.');

        // Act
        Telegram::sendMessage('test');
    }

    /**
     * @test
     */
    public function can_send_message(): void
    {
        // Arrange
        Http::fake([
            'https://api.telegram.org/bot*' => Http::response(['test-key' => 'test-value'], 200),
        ]);

        Config::set('telegram.bot_token', 'test_token');
        Config::set('telegram.chat_id', 'test_chat_id');

        // Act
        $response = Telegram::sendMessage('test');

        // Assert
        $this->assertEquals(200, $response->status());
        self::assertEquals(['test-key' => 'test-value'], $response->json());
    }

    /**
     * @test
     */
    public function can_send_exception_message(): void
    {
        // Arrange
        $message = 'exception message';
        Http::fake([
            'https://api.telegram.org/bot*' => Http::response(['test-key' => $message], 200),
        ]);

        Config::set('telegram.bot_token', 'test_token');
        Config::set('telegram.chat_id', 'test_chat_id');

        $exception = new \RuntimeException($message);

        // Act
        $response = Telegram::sendExceptionMessage($exception);

        // Assert
        $this->assertEquals(200, $response->status());
        Http::assertSent(function (Request $request) {
            return str_contains($request['text'], 'RuntimeException') &&
                str_contains($request['text'], 'File');
        });
    }
}
