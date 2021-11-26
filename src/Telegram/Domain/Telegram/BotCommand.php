<?php

declare(strict_types=1);

namespace Zorachka\Framework\Telegram\Domain\Telegram;

final class BotCommand
{
    private string $name;
    private string $description;

    public function __construct(string $name, string $description)
    {
        $this->name = $name;
        $this->description = $description;
    }

    /**
     * @param string $name Text of the command, 1-32 characters.
     * Can contain only lowercase English letters, digits and underscores.
     * @param string $description Description of the command, 3-256 characters.
     * @return static
     */
    public static function create(string $name, string $description): self
    {
        return new self($name, $description);
    }

    public function asArray(): array
    {
        return [
            'command' => $this->name,
            'description' => $this->description,
        ];
    }
}
