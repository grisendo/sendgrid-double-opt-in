<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\ContactList;

use App\Domain\ContactList\ContactListId;
use App\Infrastructure\Persistence\Doctrine\UuidType;

final class ContactListIdType extends UuidType
{
    protected function typeClassName(): string
    {
        return ContactListId::class;
    }

    protected function customTypeName(): string
    {
        return 'list_id';
    }
}
