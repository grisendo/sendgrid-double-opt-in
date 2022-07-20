<?php

declare(strict_types=1);

namespace App\Infrastructure\Bus;

use Grisendo\DDD\Bus\Event\DomainEvent;
use Grisendo\DDD\Bus\Event\EventBus;
use Symfony\Component\Messenger\MessageBusInterface;

final class SymfonyMessengerEventBus implements EventBus
{
    private MessageBusInterface $eventBus;

    private array $publishedEvents = [];

    public function __construct(MessageBusInterface $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    public function publish(DomainEvent ...$events): void
    {
        foreach ($events as $event) {
            $this->publishedEvents[] = $event;
            $this->eventBus->dispatch($event);
        }
    }

    public function getPublishedEvents(): array
    {
        return $this->publishedEvents;
    }
}
