<?php

declare(strict_types=1);

namespace App\Domain\Contact;

use App\Domain\ContactList\ContactListId;

interface ContactRepository
{
    public function findById(ContactId $id): ?Contact;

    public function findByListAndEmail(
        ContactListId $listId,
        ContactEmail $email
    ): ?Contact;

    public function save(Contact $contact): void;
}
