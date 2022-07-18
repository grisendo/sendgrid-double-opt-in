<?php

declare(strict_types=1);

namespace App\Tests\Domain;

use App\Domain\Contact\Contact;
use App\Domain\Contact\ContactId;
use App\Domain\ContactList\ContactListId;
use Grisendo\DDD\Uuid;

class ContactMother
{
    public static function random(): Contact
    {
        return new Contact(
            new ContactId(Uuid::random()),
            new ContactListId(Uuid::random()),
            ContactEmailMother::random(),
            ContactTokenMother::random(),
            ContactNameMother::random(),
            ContactSurnameMother::random()
        );
    }
}
