<?php

declare(strict_types=1);

namespace App\Domain\Contact;

use App\Domain\List\ListId;
use Grisendo\DDD\Aggregate\AggregateRoot;

class Contact extends AggregateRoot
{
    private ContactId $id;

    private ListId $listId;

    private ContactEmail $email;

    private ContactName $name;

    private ContactSurname $surname;

    public function __construct(
        ContactId $id,
        ListId $listId,
        ContactEmail $email,
        ContactName $name,
        ContactSurname $surname,
    ) {
        $this->id = $id;
        $this->listId = $listId;
        $this->email = $email;
        $this->name = $name;
        $this->surname = $surname;
    }

    public static function create(
        ContactId $id,
        ListId $listId,
        ContactEmail $email,
        ContactName $name,
        ContactSurname $surname,
    ): self {
        $contact = new self(
            $id,
            $listId,
            $email,
            $name,
            $surname
        );

        $contact->record(
            new ContactCreatedDomainEvent(
                $contact->getId()->getValue()->getValue(),
                $listId->getValue()->getValue(),
                $email->getValue(),
                $name->getValue(),
                $surname->getValue(),
            )
        );

        return $contact;
    }

    public function getId(): ContactId
    {
        return $this->id;
    }

    public function getListId(): ListId
    {
        return $this->listId;
    }

    public function getEmail(): ContactEmail
    {
        return $this->email;
    }

    public function getName(): ContactName
    {
        return $this->name;
    }

    public function getSurname(): ContactSurname
    {
        return $this->surname;
    }
}
