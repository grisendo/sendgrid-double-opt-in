<?php

declare(strict_types=1);

namespace App\Application\ContactList\GetAll;

use App\Application\ContactList\ContactListResponse;
use App\Application\ContactList\ContactListsResponse;
use App\Domain\ContactList\ContactList;
use App\Domain\ContactList\ContactListRepository;

final class AllContactListGetter
{
    private ContactListRepository $repository;

    public function __construct(ContactListRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(): ContactListsResponse
    {
        $lists = array_map(static function (ContactList $list) {
            return new ContactListResponse(
                $list->getId()->getValue()->getValue(),
                $list->getName()->getValue()
            );
        }, $this->repository->findAll());

        return new ContactListsResponse($lists);
    }
}
