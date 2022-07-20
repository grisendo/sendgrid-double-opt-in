<?php

declare(strict_types=1);

namespace App\Infrastructure\Bus;

use App\Application\Contact\Confirm\ConfirmContactCommand;
use App\Application\Contact\Confirm\ConfirmContactCommandHandler;
use App\Application\Contact\Create\CreateContactCommand;
use App\Application\Contact\Create\CreateContactCommandHandler;
use App\Application\ContactList\Import\ImportContactListsCommand;
use App\Application\ContactList\Import\ImportContactListsCommandHandler;
use Grisendo\DDD\Bus\Command\Command;
use Grisendo\DDD\Bus\Command\CommandBus;

final class InMemoryCommandBus implements CommandBus
{
    private CreateContactCommandHandler $createContactCommandHandler;

    private ConfirmContactCommandHandler $confirmContactCommandHandler;

    private ImportContactListsCommandHandler $importContactListsCommandHandler;

    public function __construct(
        CreateContactCommandHandler $createContactCommandHandler,
        ConfirmContactCommandHandler $confirmContactCommandHandler,
        ImportContactListsCommandHandler $importContactListsCommandHandler
    ) {
        $this->createContactCommandHandler = $createContactCommandHandler;
        $this->confirmContactCommandHandler = $confirmContactCommandHandler;
        $this->importContactListsCommandHandler = $importContactListsCommandHandler;
    }

    public function dispatch(Command $command): void
    {
        if ($command instanceof CreateContactCommand) {
            $this->createContactCommandHandler->__invoke($command);
        } elseif ($command instanceof ConfirmContactCommand) {
            $this->confirmContactCommandHandler->__invoke($command);
        } elseif ($command instanceof ImportContactListsCommand) {
            $this->importContactListsCommandHandler->__invoke($command);
        }
    }
}
