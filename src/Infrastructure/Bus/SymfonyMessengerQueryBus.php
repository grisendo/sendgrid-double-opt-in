<?php

declare(strict_types=1);

namespace App\Infrastructure\Bus;

use Grisendo\DDD\Bus\Query\Query;
use Grisendo\DDD\Bus\Query\QueryBus;
use Grisendo\DDD\Bus\Query\QueryResponse;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final class SymfonyMessengerQueryBus implements QueryBus
{
    private MessageBusInterface $queryBus;

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    public function ask(Query $query): ?QueryResponse
    {
        $envelope = $this->queryBus->dispatch(new Envelope($query));

        return $envelope->last(HandledStamp::class)?->getResult();
    }
}
