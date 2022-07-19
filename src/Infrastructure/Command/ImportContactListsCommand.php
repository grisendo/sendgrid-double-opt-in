<?php

declare(strict_types=1);

namespace App\Infrastructure\Command;

use App\Domain\ContactList\ContactList;
use App\Infrastructure\Persistence\DoctrineContactListRepository;
use App\Infrastructure\Persistence\SendGridApiContactListRepository;
use Grisendo\DDD\Bus\Event\EventBus;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:import-lists')]
final class ImportContactListsCommand extends Command
{
    private DoctrineContactListRepository $localRepository;

    private SendGridApiContactListRepository $remoteRepository;

    private EventBus $bus;

    public function __construct(
        DoctrineContactListRepository $localRepository,
        SendGridApiContactListRepository $remoteRepository,
        EventBus $bus
    ) {
        $this->localRepository = $localRepository;
        $this->remoteRepository = $remoteRepository;
        $this->bus = $bus;
        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $items = $this->remoteRepository->findAll();
        $created = 0;
        $renamed = 0;
        foreach ($items as $item) {
            $contactList = $this->localRepository->findById($item->getId());
            if (!$contactList) {
                $contactList = ContactList::create(
                    $item->getId(),
                    $item->getName()
                );
                ++$created;
            } else {
                $contactList->rename($item->getName());
                ++$renamed;
            }

            $this->localRepository->save($contactList);
            $this->bus->publish(...$contactList->pullDomainEvents());
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
