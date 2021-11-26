<?php

declare(strict_types=1);

namespace Zorachka\Framework\Telegram\Application\Bot;

use Zorachka\Framework\Telegram\Domain\Telegram\WebhookUrl;
use Zorachka\Framework\Telegram\UI\Bot\Command\BotCommand;

interface Telegram
{
    /**
     * @param BotCommand[] $commands
     * @return mixed
     */
    public function setMyCommands(array $commands): void;

    /**
     * @param WebhookUrl $url
     */
    public function setWebhook(WebhookUrl $url): void;
}
