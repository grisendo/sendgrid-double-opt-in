<?php

declare(strict_types=1);

namespace App\Infrastructure\Bus;

use App\Application\Contacts\Confirm\ConfirmContactCommand;
use App\Application\Contacts\Confirm\ConfirmContactCommandHandler;
use App\Application\Contacts\Create\CreateContactCommand;
use App\Application\Contacts\Create\CreateContactCommandHandler;
use Grisendo\DDD\Bus\Command\Command;
use Grisendo\DDD\Bus\Command\CommandBus;

final class InMemoryCommandBus implements CommandBus
{
    private CreateContactCommandHandler $createContactCommandHandler;

    private ConfirmContactCommandHandler $confirmContactCommandHandler;

    public function __construct(
        CreateContactCommandHandler $createContactCommandHandler,
        ConfirmContactCommandHandler $confirmContactCommandHandler
    ) {
        $this->createContactCommandHandler = $createContactCommandHandler;
        $this->confirmContactCommandHandler = $confirmContactCommandHandler;
    }

    public function dispatch(Command $command): void
    {
        if ($command instanceof CreateContactCommand) {
            $this->createContactCommandHandler->__invoke($command);
        } elseif ($command instanceof ConfirmContactCommand) {
            $this->confirmContactCommandHandler->__invoke($command);
        }
    }
}
