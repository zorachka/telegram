<?php

declare(strict_types=1);

namespace Zorachka\Framework\Telegram\Infrastructure\Telegram;

use TelegramBot\Api\BotApi;
use TelegramBot\Api\Exception;
use TelegramBot\Api\HttpException;
use TelegramBot\Api\InvalidJsonException;
use Zorachka\Framework\Telegram\Application\Bot\Telegram;
use Zorachka\Framework\Telegram\Domain\Telegram\BotCommand;
use Zorachka\Framework\Telegram\Domain\Telegram\WebhookUrl;

final class TelegramBot implements Telegram
{
    public function __construct(private BotApi $bot)
    {
    }

    /**
     * @param WebhookUrl $url
     * @throws Exception
     */
    public function setWebhook(WebhookUrl $url): void
    {
        $this->bot->setWebhook($url->asString());
    }

    /**
     * @param BotCommand[] $commands
     * @throws Exception
     * @throws HttpException
     * @throws InvalidJsonException
     */
    public function setMyCommands(array $commands): void
    {
        $list = [];
        foreach ($commands as $command) {
            $list[] = $command->asArray();
        }
        $this->bot->setMyCommands($list);
    }
}
