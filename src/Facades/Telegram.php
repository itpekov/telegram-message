<?php

namespace Itpekov\TelegramMessage\Facades;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Facade;
use Itpekov\TelegramMessage\TelegramMessage;

/**
 * @see TelegramMessage
 *
 * @method static Response sendMessage(string $message)
 * @method static Response sendExceptionMessage(\Throwable $e)
 */
class Telegram extends Facade
{
    protected static function getFacadeAccessor()
    {
        return TelegramMessage::class;
    }
}
