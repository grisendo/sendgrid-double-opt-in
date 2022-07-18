<?php

declare(strict_types=1);

namespace App\Application\Contacts\Create;

use Grisendo\DDD\Bus\Command\Command;

class CreateContactCommand implements Command
{
    private string $id;

    private string $listId;

    private string $contactEmail;

    private ?string $contactName;

    private ?string $contactSurname;

    public function __construct(
        string $id,
        string $listId,
        string $contactEmail,
        ?string $contactName = null,
        ?string $contactSurname = null
    ) {
        $this->id = $id;
        $this->listId = $listId;
        $this->contactEmail = $contactEmail;
        $this->contactName = $contactName;
        $this->contactSurname = $contactSurname;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getListId(): string
    {
        return $this->listId;
    }

    public function getContactEmail(): string
    {
        return $this->contactEmail;
    }

    public function getContactName(): ?string
    {
        return $this->contactName;
    }

    public function getContactSurname(): ?string
    {
        return $this->contactSurname;
    }
}
