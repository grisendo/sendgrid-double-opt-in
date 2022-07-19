<?php

declare(strict_types=1);

namespace App\Infrastructure\Bus;

use Grisendo\DDD\Bus\Event\DomainEvent;
use Grisendo\DDD\Bus\Event\EventBus;

final class InMemoryEventBus implements EventBus
{
    private array $publishedEvents = [];

    public function publish(DomainEvent ...$events): void
    {
        foreach ($events as $event) {
            $this->publishedEvents[] = $event;
        }
    }

    public function getPublishedEvents(): array
    {
        return $this->publishedEvents;
    }
}
