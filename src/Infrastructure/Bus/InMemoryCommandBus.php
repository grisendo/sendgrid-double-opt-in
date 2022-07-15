<?php

declare(strict_types=1);

namespace App\Infrastructure\Bus;

use App\Application\Contacts\Create\CreateContactCommandHandler;
use Grisendo\DDD\Bus\Command\Command;
use Grisendo\DDD\Bus\Command\CommandBus;

final class InMemoryCommandBus implements CommandBus
{
    private CreateContactCommandHandler $createContactCommandHandler;

    public function __construct(
        CreateContactCommandHandler $createContactCommandHandler
    ) {
        $this->createContactCommandHandler = $createContactCommandHandler;
    }

    public function dispatch(Command $command): void
    {
        // TO-DO: Switch type of $command
        $this->createContactCommandHandler->__invoke($command);
    }
}
