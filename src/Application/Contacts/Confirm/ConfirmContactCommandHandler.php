<?php

declare(strict_types=1);

namespace App\Application\Contacts\Confirm;

use App\Domain\Contact\CannotGenerateContactTokenException;
use App\Domain\Contact\ContactEmail;
use App\Domain\Contact\ContactNotFoundException;
use App\Domain\Contact\ContactToken;
use App\Domain\ContactList\ContactListId;
use Grisendo\DDD\Bus\Command\CommandHandler;
use Grisendo\DDD\Uuid;

class ConfirmContactCommandHandler implements CommandHandler
{
    private ContactConfirmer $contactConfirmer;

    public function __construct(ContactConfirmer $contactConfirmer)
    {
        $this->contactConfirmer = $contactConfirmer;
    }

    /**
     * @throws ContactNotFoundException|CannotGenerateContactTokenException
     */
    public function __invoke(ConfirmContactCommand $command): void
    {
        $listId = new ContactListId(new Uuid($command->getListId()));
        $email = new ContactEmail($command->getContactEmail());
        $token = new ContactToken($command->getContactToken());

        $this->contactConfirmer->__invoke($listId, $email, $token);
    }
}
