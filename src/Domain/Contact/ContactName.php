<?php

declare(strict_types=1);

namespace App\Domain\Contact;

use Grisendo\DDD\Validator;
use Grisendo\DDD\ValueObject\StringValueObject;
use InvalidArgumentException;

class ContactName extends StringValueObject
{
    public function __construct(string $value)
    {
        $this->ensureNameIsValid($value);
        parent::__construct($value);
    }

    private function ensureNameIsValid(string $value): void
    {
        if (Validator::hasMoreThanNCharacters($value, 50)) {
            $msg = 'Contact name length too big.';
            throw new InvalidArgumentException($msg);
        }
    }
}
