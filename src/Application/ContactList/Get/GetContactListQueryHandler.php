<?php

declare(strict_types=1);

namespace App\Application\ContactList\Get;

use App\Application\ContactList\ContactListResponse;
use App\Domain\ContactList\ContactListId;
use Grisendo\DDD\Bus\Query\QueryHandler;
use Grisendo\DDD\Uuid;

class GetContactListQueryHandler implements QueryHandler
{
    private ContactListGetter $getter;

    public function __construct(ContactListGetter $getter)
    {
        $this->getter = $getter;
    }

    public function __invoke(
        GetContactListQuery $query
    ): ContactListResponse {
        return $this->getter->__invoke(
            new ContactListId(new Uuid($query->getId()))
        );
    }
}
