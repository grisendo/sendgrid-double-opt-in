<?php

declare(strict_types=1);

namespace App\Infrastructure\Bus;

use Grisendo\DDD\Bus\Query\Query;
use Grisendo\DDD\Bus\Query\QueryBus;
use Grisendo\DDD\Bus\Query\QueryResponse;

final class InMemoryQueryBus implements QueryBus
{
    public function ask(Query $query): ?QueryResponse
    {
        return null;
    }
}
