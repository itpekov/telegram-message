<?php

namespace Itpekov\TelegramMessage;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class TelegramMessage
{
    protected const API_URL = 'https://api.telegram.org/bot';

    public function __construct(
        private readonly string $botToken,
        private readonly string $chatId
    ) {
        if (empty($this->botToken)) {
            throw new \InvalidArgumentException('Telegram bot token is required.');
        }

        if (empty($this->chatId)) {
            throw new \InvalidArgumentException('Telegram chat id is required.');
        }
    }

    public function sendMessage(string $message): Response
    {
        return $this->postRequest([
            'chat_id' => $this->chatId,
            'text' => $message,
        ]);
    }

    public function sendExceptionMessage(Throwable $throwable): Response
    {
        $data = [
            'class' => get_class($throwable),
            'message' => $throwable->getMessage(),
            'file' => $throwable->getFile(),
            'line' => $throwable->getLine(),
            'exception_code' => $throwable->getCode(),
        ];

        return $this->postRequest([
            'chat_id' => $this->chatId,
            'text' => (string) view('telegram-message::exception', $data),
            'parse_mode' => 'HTML',
        ]);
    }

    private function postRequest(array $data): Response
    {
        $response = Http::post(
            self::API_URL . $this->botToken . '/sendMessage',
            $data
        );

        return $this->handleResponse($response);
    }

    private function handleResponse(Response $response): Response
    {
        if ($response->failed()) {
            $this->logFailed($response);
        }

        return $response;
    }

    private function logFailed(Response $response): void
    {
        Log::warning(
            __CLASS__ . ' request failed',
            [
                'reason' => $response->reason(),
                'status' => $response->status(),
                'body' => Str::substr($response->body(), 0, 200),
            ]
        );
    }
}
