<?php

declare(strict_types=1);

namespace Zorachka\Framework\Telegram\UI\Console\Telegram;

use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zorachka\Framework\CommandBus\CommandBus;
use Zorachka\Framework\Telegram\Application\SetBotCommands\SetBotCommandsCommand;

final class SetCommands extends Command
{
    private CommandBus $commandBus;
    private array $list;

    public function __construct(CommandBus $commandBus, array $list)
    {
        parent::__construct();
        $this->commandBus = $commandBus;
        $this->list = $list;
    }

    /**
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName("telegram:set-commands")
            ->setDescription('Use this method to change the list of the bot\'s commands');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $command = new SetBotCommandsCommand();
            $command->list = $this->list;

            $this->commandBus->handle($command);

            $successMessage = "<info>Commands:</info>\n";

            foreach ($command->list as $name => $description) {
                $successMessage .= \sprintf("<info>– %s: %s.</info>\n", $name, $description);
            }

            $successMessage .= '<info>were successfully set up.</info>';
            $output->writeln($successMessage);

            return 0;
        } catch (Exception $exception) {
            $errorMessage = \sprintf('<error>Something went wrong: %s</error>', $exception->getMessage());
            $output->writeln($errorMessage);

            return 1;
        }
    }
}
