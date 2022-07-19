<?php

declare(strict_types=1);

namespace App\Application\ContactList\Get;

use App\Application\ContactList\ContactListResponse;
use App\Domain\ContactList\ContactListId;
use App\Domain\ContactList\ContactListNotFoundException;
use App\Domain\ContactList\ContactListRepository;

final class ContactListGetter
{
    private ContactListRepository $repository;

    public function __construct(ContactListRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(ContactListId $id): ContactListResponse
    {
        $list = $this->repository->findById($id);
        if (!$list) {
            throw new ContactListNotFoundException($id);
        }

        return new ContactListResponse(
            $list->getId()->getValue()->getValue(),
            $list->getName()->getValue()
        );
    }
}
