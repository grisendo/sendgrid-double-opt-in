<?php

declare(strict_types=1);

namespace App\Domain\Contact;

interface ContactRepository
{
    public function findById(ContactId $contactId): ?Contact;

    public function save(Contact $contact): void;
}
