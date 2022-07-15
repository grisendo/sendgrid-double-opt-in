<?php

declare(strict_types=1);

namespace App\Application\Contacts\Create;

use App\Domain\Contact\Contact;
use App\Domain\Contact\ContactEmail;
use App\Domain\Contact\ContactId;
use App\Domain\Contact\ContactName;
use App\Domain\Contact\ContactRepository;
use App\Domain\Contact\ContactSurname;
use App\Domain\List\ListId;
use Grisendo\DDD\Bus\Event\EventBus;

final class ContactCreator
{
    private ContactRepository $repository;

    private EventBus $bus;

    public function __construct(ContactRepository $repository, EventBus $bus)
    {
        $this->repository = $repository;
        $this->bus = $bus;
    }

    public function __invoke(
        ContactId $id,
        ListId $listId,
        ContactEmail $email,
        ContactName $name,
        ContactSurname $surname,
    ): void {
        if (null !== $this->repository->findById($id)) {
            return;
        }
        $contact = Contact::create($id, $listId, $email, $name, $surname);

        $this->repository->save($contact);
        $this->bus->publish(...$contact->pullDomainEvents());
    }
}
