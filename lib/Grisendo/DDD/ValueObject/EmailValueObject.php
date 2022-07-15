<?php

declare(strict_types=1);

namespace Grisendo\DDD\ValueObject;

use Grisendo\DDD\Validator;
use InvalidArgumentException;

abstract class EmailValueObject extends StringValueObject
{
    public function __construct(string $email)
    {
        $this->ensureEmailIsValid($email);
        parent::__construct($email);
    }

    private function ensureEmailIsValid(string $email): void
    {
        if (!Validator::isValidEmail($email)) {
            $msg = sprintf('Invalid email: %s', $email);
            throw new InvalidArgumentException($msg);
        }
    }
}
