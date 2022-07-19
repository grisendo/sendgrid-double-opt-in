<?php

declare(strict_types=1);

namespace App\Application\ContactList;

use Grisendo\DDD\Bus\Query\QueryResponse;

final class ContactListResponse implements QueryResponse
{
    private string $id;

    private string $name;

    public function __construct(string $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
