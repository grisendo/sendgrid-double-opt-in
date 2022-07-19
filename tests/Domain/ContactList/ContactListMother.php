<?php

declare(strict_types=1);

namespace App\Tests\Domain\ContactList;

use App\Domain\ContactList\ContactList;

class ContactListMother
{
    public static function random(): ContactList
    {
        return new ContactList(
            ContactListIdMother::random(),
            ContactListNameMother::random(),
        );
    }

    public static function randomRenaming(
        ContactList $contactList
    ): ContactList {
        return new ContactList(
            $contactList->getId(),
            ContactListNameMother::random(),
        );
    }
}
