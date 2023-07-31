<?php

declare(strict_types=1);

namespace Zorachka\Telegram\Application\Bot;

use Zorachka\Telegram\Domain\Telegram\Message;
use Zorachka\Telegram\Domain\Telegram\WebhookUrl;
use Zorachka\Telegram\UI\Bot\Command\BotCommand;

interface Telegram
{
    public function sendMessage(Message $message): void;

    /**
     * @param BotCommand[] $commands
     */
    public function setMyCommands(array $commands): void;

    public function setWebhook(WebhookUrl $url): void;
}
