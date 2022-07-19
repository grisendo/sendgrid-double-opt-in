<?php

declare(strict_types=1);

namespace App\Domain\ContactList;

use Grisendo\DDD\Aggregate\AggregateRoot;

class ContactList extends AggregateRoot
{
    private ContactListId $id;

    private ContactListName $name;

    public function __construct(
        ContactListId $id,
        ContactListName $name,
    ) {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): ContactListId
    {
        return $this->id;
    }

    public function getName(): ContactListName
    {
        return $this->name;
    }
}
