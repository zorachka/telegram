<?php

declare(strict_types=1);

namespace Zorachka\Framework\Telegram;

use Closure;
use Psr\Container\ContainerInterface;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Client;
use Zorachka\Framework\CommandBus\CommandBus;
use Zorachka\Framework\CommandBus\CommandBusConfig;
use Zorachka\Framework\Console\ConsoleConfig;
use Zorachka\Framework\Container\ServiceProvider;
use Zorachka\Framework\Http\Router\Route;
use Zorachka\Framework\Http\Router\RouterConfig;
use Zorachka\Framework\Telegram\Application\Bot\Telegram;
use Zorachka\Framework\Telegram\Application\SetBotCommands\SetBotCommandsCommand;
use Zorachka\Framework\Telegram\Application\SetBotCommands\SetBotCommandsHandler;
use Zorachka\Framework\Telegram\Application\SetBotWebhook\SetBotWebhookCommand;
use Zorachka\Framework\Telegram\Application\SetBotWebhook\SetBotWebhookHandler;
use Zorachka\Framework\Telegram\Infrastructure\Telegram\TelegramBot;
use Zorachka\Framework\Telegram\UI\Bot\Command\BotCommand;
use Zorachka\Framework\Telegram\UI\Console\Telegram\SetCommands;
use Zorachka\Framework\Telegram\UI\Console\Telegram\SetWebhookCommand;
use Zorachka\Framework\Telegram\UI\Http\ListenTelegramBotAction;

final class TelegramServiceProvider implements ServiceProvider
{
    /**
     * @inheritDoc
     */
    public static function getDefinitions(): array
    {
        return [
            Client::class => static function (ContainerInterface $container) {
                /** @var TelegramConfig $config */
                $config = $container->get(TelegramConfig::class);

                $bot = new Client($config->token());

                $commands = $config->commands();

                foreach ($commands as $callable) {
                    /** @var BotCommand $command */
                    $command = $container->get($callable);
                    $name = $command::name();

                    //Handle /$name command
                    $bot->command($name, Closure::fromCallable($command));
                }

                foreach ($config->listeners() as $listener) {
                    // Handle any messages
                    $bot->on(Closure::fromCallable($container->get($listener)), function () {
                        return true;
                    });
                }

                return $bot;
            },
            BotApi::class => static function (ContainerInterface $container) {
                /** @var TelegramConfig $config */
                $config = $container->get(TelegramConfig::class);

                return new BotApi($config->token());
            },
            SetCommands::class => static function (ContainerInterface $container) {
                /** @var TelegramConfig $config */
                $config = $container->get(TelegramConfig::class);

                /** @var CommandBus $commandBus */
                $commandBus = $container->get(CommandBus::class);
                $list = [];

                /** @var BotCommand $command */
                foreach ($config->commands() as $command) {
                    $name = $command::name();
                    $description = $command::description();

                    $list[$name] = $description;
                }

                return new SetCommands($commandBus, $list);
            },
            Telegram::class => static function (ContainerInterface $container) {
                $bot = $container->get(BotApi::class);

                return new TelegramBot($bot);
            },
            TelegramConfig::class => static fn() => TelegramConfig::withDefaults(),
        ];
    }

    /**
     * @inheritDoc
     */
    public static function getExtensions(): array
    {
        return [
            ConsoleConfig::class => static function(ConsoleConfig $config) {
                return $config
                    ->withCommand(SetCommands::class)
                    ->withCommand(SetWebhookCommand::class);
            },
            CommandBusConfig::class => static function(CommandBusConfig $config) {
                return $config
                    ->withHandlerFor(SetBotCommandsCommand::class, SetBotCommandsHandler::class)
                    ->withHandlerFor(SetBotWebhookCommand::class, SetBotWebhookHandler::class);
            },
            RouterConfig::class => static function(RouterConfig $config, ContainerInterface $container) {
                /** @var TelegramConfig $telegramConfig */
                $telegramConfig = $container->get(TelegramConfig::class);

                return $config
                    ->addRoute(
                        Route::post('/bot/' . $telegramConfig->token() . '/listen', ListenTelegramBotAction::class)
                    );
            },
        ];
    }
}
