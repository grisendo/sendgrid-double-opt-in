<?php

declare(strict_types=1);

namespace App\Domain\ContactList;

interface ContactListRepository
{
    public function findById(ContactListId $id): ?ContactList;

    public function findAll(): array;
}
