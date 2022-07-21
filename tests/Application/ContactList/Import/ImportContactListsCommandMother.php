<?php

declare(strict_types=1);

namespace App\Tests\Application\ContactList\Import;

use App\Application\ContactList\Import\ImportContactListsCommand;

class ImportContactListsCommandMother
{
    public static function generate(): ImportContactListsCommand
    {
        return new ImportContactListsCommand();
    }
}
