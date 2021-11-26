<?php

declare(strict_types=1);

namespace Zorachka\Framework\Telegram\UI\Console\Telegram;

use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zorachka\Framework\CommandBus\CommandBus;
use Zorachka\Framework\Telegram\Application\SetBotWebhook\SetBotWebhookCommand;

final class SetWebhookCommand extends Command
{
    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
        parent::__construct();
    }

    /**
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName("telegram:set-webhook '<url>'")
            ->setDescription('Specify a url and receive incoming updates via an outgoing webhook')
            ->addArgument('url', InputArgument::REQUIRED, 'Webhook url');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            /** @var string $url */
            $url = $input->getArgument('url');

            $command = new SetBotWebhookCommand();
            $command->url = $url;

            $this->commandBus->handle($command);

            $output->writeln(\sprintf('<info>Webhook "%s" was successfully set up.</info>', $url));

            return 0;
        } catch (Exception $exception) {
            $output->writeln(\sprintf('<error>Something went wrong: %s</error>', $exception->getMessage()));

            return 1;
        }
    }
}
