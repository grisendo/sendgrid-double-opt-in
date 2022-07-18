<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Contact;

use App\Domain\Contact\ContactName;
use App\Infrastructure\Persistence\Doctrine\NullableStringType;

final class ContactNameType extends NullableStringType
{
    protected function getLength(): ?int
    {
        return 50;
    }

    protected function typeClassName(): string
    {
        return ContactName::class;
    }

    protected function customTypeName(): string
    {
        return 'contact_name';
    }
}
