<?php

declare(strict_types=1);

namespace App\Domain\ContactList;

use Grisendo\DDD\Validator;
use Grisendo\DDD\ValueObject\StringValueObject;
use InvalidArgumentException;

class ContactListName extends StringValueObject
{
    public function __construct(string $value)
    {
        $this->ensureNameIsValid($value);
        parent::__construct($value);
    }

    public function isEqualsTo(ContactListName $name): bool
    {
        return $this->value === $name->getValue();
    }

    private function ensureNameIsValid(string $value): void
    {
        if (Validator::hasMoreThanNCharacters($value, 100)) {
            $msg = 'Contact list name length too big.';
            throw new InvalidArgumentException($msg);
        }
    }
}
