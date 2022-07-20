<?php

declare(strict_types=1);

namespace App\Application\ContactList\Import;

use Grisendo\DDD\Bus\Command\CommandHandler;

class ImportContactListsCommandHandler implements CommandHandler
{
    private ContactListImporter $importer;

    public function __construct(ContactListImporter $importer)
    {
        $this->importer = $importer;
    }

    public function __invoke(ImportContactListsCommand $command): void
    {
        $this->importer->__invoke();
    }
}
