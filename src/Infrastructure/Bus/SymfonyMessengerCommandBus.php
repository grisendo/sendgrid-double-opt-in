<?php

declare(strict_types=1);

namespace App\Infrastructure\Bus;

use Grisendo\DDD\Bus\Command\Command;
use Grisendo\DDD\Bus\Command\CommandBus;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

final class SymfonyMessengerCommandBus implements CommandBus
{
    private MessageBusInterface $commandBus;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @throws Throwable
     */
    public function dispatch(Command $command): void
    {
        try {
            $this->commandBus->dispatch($command);
        } catch (HandlerFailedException $e) {
            while ($e instanceof HandlerFailedException) {
                /** @var Throwable $e */
                $e = $e->getPrevious();
            }

            throw $e;
        }
    }
}
