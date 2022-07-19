<?php

declare(strict_types=1);

namespace App\Application\ContactList\GetAll;

use App\Application\ContactList\ContactListsResponse;
use Grisendo\DDD\Bus\Query\QueryHandler;

class GetAllContactListsQueryHandler implements QueryHandler
{
    private AllContactListGetter $getter;

    public function __construct(AllContactListGetter $getter)
    {
        $this->getter = $getter;
    }

    public function __invoke(
        GetAllContactListsQuery $query
    ): ContactListsResponse {
        return $this->getter->__invoke();
    }
}
