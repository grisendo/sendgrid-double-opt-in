<?php

declare(strict_types=1);

namespace App\Application\ContactList\Import;

use App\Domain\ContactList\ContactList;
use App\Domain\ContactList\ContactListRepository;
use Grisendo\DDD\Bus\Event\EventBus;

final class ContactListImporter
{
    private ContactListRepository $localRepository;

    private ContactListRepository $remoteRepository;

    private EventBus $bus;

    public function __construct(
        ContactListRepository $localRepository,
        ContactListRepository $remoteRepository,
        EventBus $bus
    ) {
        $this->localRepository = $localRepository;
        $this->remoteRepository = $remoteRepository;
        $this->bus = $bus;
    }

    public function __invoke(): void
    {
        $items = $this->remoteRepository->findAll();
        foreach ($items as $item) {
            $contactList = $this->localRepository->findById($item->getId());
            if (!$contactList) {
                $contactList = ContactList::create(
                    $item->getId(),
                    $item->getName()
                );
            } else {
                $contactList->rename($item->getName());
            }

            $this->localRepository->save($contactList);
            $this->bus->publish(...$contactList->pullDomainEvents());
        }
    }
}
