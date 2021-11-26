<?php

declare(strict_types=1);

namespace Zorachka\Framework\Telegram\Domain\Telegram;

final class Message
{
    private ChatId $chatId;
    private Text $text;
    private ParseMode $parseMode;
    private bool $isSilent;

    private function __construct(
        ChatId $chatId,
        Text $text,
        ParseMode $parseMode,
        bool $isSilent = false
    ) {
        $this->chatId = $chatId;
        $this->text = $text;
        $this->parseMode = $parseMode;
        $this->isSilent = $isSilent;
    }

    /**
     * @param ChatId $chatId
     * @param Text $text
     * @param ParseMode $parseMode
     * @param bool $isSilent
     * @return static
     */
    public static function create(
        ChatId $chatId,
        Text $text,
        ParseMode $parseMode,
        bool $isSilent = false
    ): self {
        return new self($chatId, $text, $parseMode, $isSilent);
    }

    /**
     * @return ChatId
     */
    public function chatId(): ChatId
    {
        return $this->chatId;
    }

    /**
     * @return Text
     */
    public function text(): Text
    {
        return $this->text;
    }

    /**
     * @return string
     */
    public function parseMode(): string
    {
        return $this->parseMode->name();
    }

    /**
     * Sends the message silently.
     * Users will receive a notification with no sound.
     * @return bool
     */
    public function isSilent(): bool
    {
        return $this->isSilent;
    }

    /**
     * Makes message silent.
     */
    public function disableNotification(): void
    {
        $this->isSilent = true;
    }

    /**
     * Makes message not silent.
     */
    public function enableNotification(): void
    {
        $this->isSilent = false;
    }
}
