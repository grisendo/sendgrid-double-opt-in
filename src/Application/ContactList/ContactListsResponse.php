<?php

declare(strict_types=1);

namespace App\Application\ContactList;

use Grisendo\DDD\Bus\Query\QueryResponse;

final class ContactListsResponse implements QueryResponse
{
    private array $contactLists;

    public function __construct(array $contactLists)
    {
        $this->contactLists = $contactLists;
    }

    public function getContactLists(): array
    {
        return $this->contactLists;
    }
}
