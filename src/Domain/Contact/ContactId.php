<?php

declare(strict_types=1);

namespace App\Domain\Contact;

use Grisendo\DDD\ValueObject\UuidValueObject;

class ContactId extends UuidValueObject
{
    public function isEqualsTo(ContactId $id): bool
    {
        return $id->getValue()->equals($this->getValue());
    }
}
