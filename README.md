# Telegram message

[![Latest Version on Packagist](https://img.shields.io/packagist/v/itpekov/telegram-message.svg?style=flat-square)](https://packagist.org/packages/itpekov/telegram-message)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/itpekov/telegram-message/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/itpekov/telegram-message/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/itpekov/telegram-message/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/itpekov/telegram-message/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
## Installation

You can install the package via composer:

```bash
composer require itpekov/telegram-message
```

Add in `.env` file variables:
```dotenv
TELEGRAM_CHAT_ID=your_chat_id
TELEGRAM_BOT_TOKEN=your_bot_token
```

## Usage

Scenario 1: send message from any place with:

```php
Telegram::sendMessage('Your message');
```

Scenario 2: send exception messages with

```php
Telegram::sendExceptionMessage($e);
```

Scenario 3: send all unhandled exceptions from your app
by adding/updating `report` method in `Handler.php` class

```php
use Itpekov\TelegramMessage\Facades\Telegram;

public function report(Throwable $e)
{
    $e = $this->mapException($e);

    if ($this->shouldntReport($e)) {
        return;
    }

    Telegram::sendExceptionMessage($e);

    $this->reportThrowable($e);
}
```

## Customization

Optionally, You can publish the config file with:

```bash
php artisan vendor:publish --provider="Itpekov\TelegramMessage\TelegramMessageServiceProvider" --tag="config"
```

This is the contents of the published config file:

```php
return [
    'chat_id' => env('TELEGRAM_CHAT_ID'),
    'bot_token' => env('TELEGRAM_BOT_TOKEN'),
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --provider="Itpekov\TelegramMessage\TelegramMessageServiceProvider" --tag="views"
```


## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [itpekov](https://github.com/itpekov)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
