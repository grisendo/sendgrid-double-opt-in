<?php

declare(strict_types=1);

namespace App\Infrastructure\Bus;

use Grisendo\DDD\Bus\Event\DomainEvent;
use Grisendo\DDD\Bus\Event\EventBus;

final class InMemoryEventBus implements EventBus
{
    public function publish(DomainEvent ...$events): void
    {
    }
}
