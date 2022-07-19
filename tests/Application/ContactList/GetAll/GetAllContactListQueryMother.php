<?php

declare(strict_types=1);

namespace App\Tests\Application\ContactList\GetAll;

use App\Application\ContactList\GetAll\GetAllContactListsQuery;

class GetAllContactListQueryMother
{
    public static function generate(): GetAllContactListsQuery
    {
        return new GetAllContactListsQuery();
    }
}
