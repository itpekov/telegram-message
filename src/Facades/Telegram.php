<?php

namespace Itpekov\TelegramMessage\Facades;

use Illuminate\Support\Facades\Facade;
use Itpekov\TelegramMessage\TelegramMessage;

/**
 * @see TelegramMessage
 *
 * @method static sendMessage(string $message)
 * @method static sendExceptionMessage(\Throwable $e)
 */
class Telegram extends Facade
{
    protected static function getFacadeAccessor()
    {
        return TelegramMessage::class;
    }
}
