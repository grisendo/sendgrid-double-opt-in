<?php

declare(strict_types=1);

namespace App\Application\Contacts\Create;

use App\Domain\Contact\ContactEmail;
use App\Domain\Contact\ContactId;
use App\Domain\Contact\ContactName;
use App\Domain\Contact\ContactSurname;
use App\Domain\List\ListId;
use Grisendo\DDD\Bus\Command\CommandHandler;
use Grisendo\DDD\Uuid;

class CreateContactCommandHandler implements CommandHandler
{
    private ContactCreator $contactCreator;

    public function __construct(ContactCreator $contactCreator)
    {
        $this->contactCreator = $contactCreator;
    }

    public function __invoke(CreateContactCommand $command): void
    {
        $id = new ContactId(new Uuid($command->getId()));
        $listId = new ListId(new Uuid($command->getListId()));
        $email = new ContactEmail($command->getContactEmail());
        $name = new ContactName($command->getContactName());
        $surname = new ContactSurname($command->getContactSurname());

        $this->contactCreator->__invoke($id, $listId, $email, $name, $surname);
    }
}
