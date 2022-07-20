<?php

declare(strict_types=1);

namespace App\Infrastructure\Bus;

use Grisendo\DDD\Bus\Command\Command;
use Grisendo\DDD\Bus\Command\CommandBus;
use Symfony\Component\Messenger\MessageBusInterface;

final class SymfonyMessengerCommandBus implements CommandBus
{
    private MessageBusInterface $commandBus;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function dispatch(Command $command): void
    {
        $this->commandBus->dispatch($command);
    }
}
