<?php

declare(strict_types=1);

namespace Grisendo\DDD\Bus\Event;

interface DomainEventSubscriber
{
    public static function subscribedTo(): array;
}
