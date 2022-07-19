<?php

declare(strict_types=1);

namespace App\Infrastructure\Bus;

use App\Application\ContactList\Get\GetContactListQuery;
use App\Application\ContactList\Get\GetContactListQueryHandler;
use App\Application\ContactList\GetAll\GetAllContactListsQuery;
use App\Application\ContactList\GetAll\GetAllContactListsQueryHandler;
use Grisendo\DDD\Bus\Query\Query;
use Grisendo\DDD\Bus\Query\QueryBus;
use Grisendo\DDD\Bus\Query\QueryResponse;

final class InMemoryQueryBus implements QueryBus
{
    private GetAllContactListsQueryHandler $getAllContactListsQueryHandler;

    private GetContactListQueryHandler $getContactListQueryHandler;

    public function __construct(
        GetAllContactListsQueryHandler $getAllContactListsQueryHandler,
        GetContactListQueryHandler $getContactListQueryHandler
    ) {
        $this->getAllContactListsQueryHandler = $getAllContactListsQueryHandler;
        $this->getContactListQueryHandler = $getContactListQueryHandler;
    }

    public function ask(Query $query): ?QueryResponse
    {
        if ($query instanceof GetAllContactListsQuery) {
            return $this->getAllContactListsQueryHandler->__invoke($query);
        }

        if ($query instanceof GetContactListQuery) {
            return $this->getContactListQueryHandler->__invoke($query);
        }

        return null;
    }
}
