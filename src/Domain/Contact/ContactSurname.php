<?php

declare(strict_types=1);

namespace App\Domain\Contact;

use Grisendo\DDD\Validator;
use Grisendo\DDD\ValueObject\StringValueObject;
use InvalidArgumentException;

class ContactSurname extends StringValueObject
{
    public function __construct(string $value)
    {
        $this->ensureSurnameIsValid($value);
        parent::__construct($value);
    }

    private function ensureSurnameIsValid(string $value): void
    {
        if (Validator::hasMoreThanNCharacters($value, 50)) {
            $msg = 'Contact surname length too big.';
            throw new InvalidArgumentException($msg);
        }
    }
}
