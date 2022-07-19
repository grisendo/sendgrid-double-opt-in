<?php

declare(strict_types=1);

namespace App\Application\Contact\Confirm;

use App\Domain\Contact\CannotGenerateContactTokenException;
use App\Domain\Contact\ContactEmail;
use App\Domain\Contact\ContactNotFoundException;
use App\Domain\Contact\ContactRepository;
use App\Domain\Contact\ContactToken;
use App\Domain\ContactList\ContactListId;
use Grisendo\DDD\Bus\Event\EventBus;

final class ContactConfirmer
{
    private ContactRepository $repository;

    private EventBus $bus;

    public function __construct(ContactRepository $repository, EventBus $bus)
    {
        $this->repository = $repository;
        $this->bus = $bus;
    }

    /**
     * @throws ContactNotFoundException|CannotGenerateContactTokenException
     */
    public function __invoke(
        ContactListId $listId,
        ContactEmail $email,
        ContactToken $token,
    ): void {
        $contact = $this->repository->findByListAndEmail($listId, $email);
        if (!$contact || !$token->isEqualsTo($contact->getToken())) {
            throw new ContactNotFoundException($listId, $email);
        }

        $contact->confirm();
        $this->repository->save($contact);
        $this->bus->publish(...$contact->pullDomainEvents());
    }
}
