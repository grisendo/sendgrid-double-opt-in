<?php

declare(strict_types=1);

namespace App\Domain\ContactList;

use Grisendo\DDD\ValueObject\UuidValueObject;

class ContactListId extends UuidValueObject
{
    public function isEqualsTo(ContactListId $listId): bool
    {
        return $listId->getValue()->equals($this->getValue());
    }
}
