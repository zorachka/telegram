<?php

declare(strict_types=1);

namespace Zorachka\Telegram\Application\SetBotWebhook;

use Zorachka\Telegram\Application\Bot\Telegram;
use Zorachka\Telegram\Domain\Telegram\WebhookUrl;

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
