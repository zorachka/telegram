<?php

declare(strict_types=1);

namespace Zorachka\Framework\Telegram\UI\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TelegramBot\Api\Client;
use TelegramBot\Api\InvalidJsonException;
use Zorachka\Framework\Http\Response\ResponseFactory;

final class ListenTelegramBotAction implements RequestHandlerInterface
{
    private Client $client;
    private ResponseFactory $responseFactory;

    public function __construct(Client $client, ResponseFactory $responseFactory)
    {
        $this->client = $client;
        $this->responseFactory = $responseFactory;
    }

    /**
     * @inheritDoc
     * @throws InvalidJsonException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->client->run();

        return $this->responseFactory->empty();
    }
}
