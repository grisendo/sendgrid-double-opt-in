<?php

declare(strict_types=1);

namespace App\Application\Contact\Confirm;

use Grisendo\DDD\Bus\Command\Command;

class ConfirmContactCommand implements Command
{
    private string $listId;

    private string $contactEmail;

    private string $contactToken;

    public function __construct(
        string $listId,
        string $contactEmail,
        string $contactToken,
    ) {
        $this->listId = $listId;
        $this->contactEmail = $contactEmail;
        $this->contactToken = $contactToken;
    }

    public function getListId(): string
    {
        return $this->listId;
    }

    public function getContactEmail(): string
    {
        return $this->contactEmail;
    }

    public function getContactToken(): ?string
    {
        return $this->contactToken;
    }
}
