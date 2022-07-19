<?php

declare(strict_types=1);

namespace App\Tests\Domain\Contact;

use App\Domain\Contact\Contact;
use App\Tests\Domain\ContactList\ContactListIdMother;

class ContactMother
{
    public static function random(): Contact
    {
        return new Contact(
            ContactIdMother::random(),
            ContactListIdMother::random(),
            ContactEmailMother::random(),
            ContactTokenMother::random(),
            ContactNameMother::random(),
            ContactSurnameMother::random()
        );
    }
}
