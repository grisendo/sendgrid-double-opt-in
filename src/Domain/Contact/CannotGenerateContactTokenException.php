<?php

declare(strict_types=1);

namespace App\Domain\Contact;

use Grisendo\DDD\DomainException;

class CannotGenerateContactTokenException extends DomainException
{
    public function getErrorCode(): string
    {
        return 'contact_cannot_generate_token';
    }

    protected function getErrorMessage(): string
    {
        return 'Cannot generate a contact token';
    }
}
