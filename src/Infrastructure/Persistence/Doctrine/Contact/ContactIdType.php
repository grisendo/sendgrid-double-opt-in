<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Contact;

use App\Domain\Contact\ContactId;
use App\Infrastructure\Persistence\Doctrine\UuidType;

final class ContactIdType extends UuidType
{
    protected function typeClassName(): string
    {
        return ContactId::class;
    }

    protected function customTypeName(): string
    {
        return 'contact_id';
    }
}
