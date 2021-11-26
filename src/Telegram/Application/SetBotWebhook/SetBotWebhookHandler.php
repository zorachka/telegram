<?php

declare(strict_types=1);

namespace Zorachka\Framework\Telegram\Application\SetBotWebhook;

use Zorachka\Framework\Telegram\Application\Bot\Telegram;
use Zorachka\Framework\Telegram\Domain\Telegram\WebhookUrl;

final class SetBotWebhookHandler
{
    public function __construct(private Telegram $telegram)
    {
    }

    public function __invoke(SetBotWebhookCommand $command): void
    {
        $this->telegram->setWebhook(
            WebhookUrl::fromString($command->url)
        );
    }
}
