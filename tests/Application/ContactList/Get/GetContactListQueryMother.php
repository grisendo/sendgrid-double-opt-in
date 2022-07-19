<?php

declare(strict_types=1);

namespace App\Tests\Application\ContactList\Get;

use App\Application\ContactList\Get\GetContactListQuery;
use App\Domain\ContactList\ContactList;
use App\Domain\ContactList\ContactListId;
use App\Tests\Domain\ContactList\ContactListIdMother;

class GetContactListQueryMother
{
    public static function fromContactListId(
        ContactListId $contactListId
    ): GetContactListQuery {
        return new GetContactListQuery(
            $contactListId->getValue()->getValue()
        );
    }

    public static function fromContactList(
        ContactList $contactList
    ): GetContactListQuery {
        return new GetContactListQuery(
            $contactList->getId()->getValue()->getValue()
        );
    }

    public static function random(): GetContactListQuery
    {
        return new GetContactListQuery(
            ContactListIdMother::random()->getValue()->getValue()
        );
    }
}
