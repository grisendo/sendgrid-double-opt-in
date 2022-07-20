<?php

declare(strict_types=1);

namespace App\Infrastructure\Command;

use App\Application\ContactList\Import\ImportContactListsCommand;
use App\Domain\ContactList\ContactListCreatedDomainEvent;
use App\Domain\ContactList\ContactListRenamedDomainEvent;
use Grisendo\DDD\Bus\Command\CommandBus;
use Grisendo\DDD\Bus\Event\EventBus;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:import-lists')]
final class ImportContactListsConsoleCommand extends Command
{
    private CommandBus $commandBus;

    private EventBus $eventBus;

    public function __construct(CommandBus $commandBus, EventBus $eventBus)
    {
        $this->commandBus = $commandBus;
        $this->eventBus = $eventBus;
        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->commandBus->dispatch(new ImportContactListsCommand());

        $events = $this->eventBus->getPublishedEvents();
        $created = 0;
        $renamed = 0;
        foreach ($events as $event) {
            if ($event instanceof ContactListCreatedDomainEvent) {
                ++$created;
                continue;
            }
            if ($event instanceof ContactListRenamedDomainEvent) {
                ++$renamed;
            }
        }
        $output->writeln('<info>Import result:</info>');
        $output->writeln(
            sprintf('<fg=cyan>  - %d list(s) created</>', $created)
        );
        $output->writeln(
            sprintf('<fg=cyan>  - %d list(s) renamed</>', $renamed)
        );

        return Command::SUCCESS;
    }
}
