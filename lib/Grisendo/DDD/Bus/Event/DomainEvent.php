<?php

declare(strict_types=1);

namespace Grisendo\DDD\Bus\Event;

use Grisendo\DDD\DateTime;
use Grisendo\DDD\Uuid;

abstract class DomainEvent
{
    private readonly string $eventId;

    private readonly string $occurredOn;

    public function __construct(
        private readonly string $aggregateId,
        string $eventId = null,
        string $occurredOn = null
    ) {
        $this->eventId = $eventId ?: Uuid::random()->getValue();
        $this->occurredOn = $occurredOn ?: (new DateTime())->toString();
    }

    abstract public static function fromPrimitives(
        string $aggregateId,
        array $body,
        string $eventId,
        string $occurredOn
    ): self;

    abstract public static function getEventName(): string;

    abstract public function toPrimitives(): array;

    public function getAggregateId(): string
    {
        return $this->aggregateId;
    }

    public function getEventId(): string
    {
        return $this->eventId;
    }

    public function getOccurredOn(): string
    {
        return $this->occurredOn;
    }
}
