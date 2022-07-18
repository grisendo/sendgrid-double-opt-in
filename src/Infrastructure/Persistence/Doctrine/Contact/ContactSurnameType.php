<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Contact;

use App\Domain\Contact\ContactSurname;
use App\Infrastructure\Persistence\Doctrine\NullableStringType;

final class ContactSurnameType extends NullableStringType
{
    protected function getLength(): ?int
    {
        return 50;
    }

    protected function typeClassName(): string
    {
        return ContactSurname::class;
    }

    protected function customTypeName(): string
    {
        return 'contact_surname';
    }
}
