<?php

declare(strict_types=1);

namespace App\Application\Contact\Create;

use App\Domain\Contact\CannotGenerateContactTokenException;
use App\Domain\Contact\Contact;
use App\Domain\Contact\ContactEmail;
use App\Domain\Contact\ContactId;
use App\Domain\Contact\ContactName;
use App\Domain\Contact\ContactRepository;
use App\Domain\Contact\ContactSurname;
use App\Domain\ContactList\ContactListId;
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

    /**
     * @throws CannotGenerateContactTokenException
     */
    public function __invoke(
        ContactId $id,
        ContactListId $listId,
        ContactEmail $email,
        ?ContactName $name = null,
        ?ContactSurname $surname = null,
    ): void {
        if (null !== $this->repository->findById($id)) {
            return;
        }
        if ($contact = $this->repository->findByListAndEmail($listId, $email)) {
            $contact->regenerateToken();
        } else {
            $contact = Contact::create($id, $listId, $email, $name, $surname);
        }

        $this->repository->save($contact);
        $this->bus->publish(...$contact->pullDomainEvents());
    }
}
