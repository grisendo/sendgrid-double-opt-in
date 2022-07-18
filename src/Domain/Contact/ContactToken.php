<?php

declare(strict_types=1);

namespace App\Domain\Contact;

use Grisendo\DDD\Validator;
use Grisendo\DDD\ValueObject\StringValueObject;
use InvalidArgumentException;

class ContactToken extends StringValueObject
{
    public function __construct(string $value)
    {
        $this->ensureTokenIsValid($value);
        parent::__construct($value);
    }

    private function ensureTokenIsValid(string $value): void
    {
        if (Validator::hasMoreThanNCharacters($value, 64)) {
            $msg = 'Contact token length too big.';
            throw new InvalidArgumentException($msg);
        }
    }
}
