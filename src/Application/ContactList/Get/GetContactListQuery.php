<?php

declare(strict_types=1);

namespace App\Application\ContactList\Get;

use Grisendo\DDD\Bus\Query\Query;

final class GetContactListQuery implements Query
{
    private string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
