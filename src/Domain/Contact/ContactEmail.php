<?php

declare(strict_types=1);

namespace App\Domain\Contact;

use Grisendo\DDD\Validator;
use Grisendo\DDD\ValueObject\EmailValueObject;
use InvalidArgumentException;

class ContactEmail extends EmailValueObject
{
    public function __construct(string $email)
    {
        if (Validator::hasMoreThanNCharacters($email, 254)) {
            throw new InvalidArgumentException('Contact email length too big.');
        }
        parent::__construct($email);
    }
}
