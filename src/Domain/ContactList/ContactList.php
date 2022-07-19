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

    public static function create(
        ContactListId $id,
        ContactListName $name
    ): self {
        $contactList = new self($id, $name);

        $contactList->record(
            new ContactListCreatedDomainEvent(
                $contactList->id->getValue()->getValue(),
                $contactList->name->getValue()
            )
        );

        return $contactList;
    }

    public function rename(ContactListName $name): void
    {
        if ($this->name->isEqualsTo($name)) {
            return;
        }
        $this->name = $name;

        $this->record(
            new ContactListRenamedDomainEvent(
                $this->id->getValue()->getValue(),
                $this->name->getValue()
            )
        );
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
