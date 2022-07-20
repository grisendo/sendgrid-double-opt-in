<?php

declare(strict_types=1);

namespace Grisendo\DDD\Bus\Event;

interface EventBus
{
    public function publish(DomainEvent ...$events): void;

    public function getPublishedEvents(): array;
}
