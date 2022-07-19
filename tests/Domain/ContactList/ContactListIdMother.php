<?php

declare(strict_types=1);

namespace App\Tests\Domain\ContactList;

use App\Domain\ContactList\ContactListId;
use Grisendo\DDD\Uuid;

class ContactListIdMother
{
    public static function random(): ContactListId
    {
        return new ContactListId(Uuid::random());
    }
}
