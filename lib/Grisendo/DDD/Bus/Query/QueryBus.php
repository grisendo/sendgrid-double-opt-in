<?php

declare(strict_types=1);

namespace Grisendo\DDD\Bus\Query;

interface QueryBus
{
    public function ask(Query $query): ?QueryResponse;
}
