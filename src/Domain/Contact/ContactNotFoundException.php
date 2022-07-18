<?php

declare(strict_types=1);

namespace App\Domain\Contact;

use App\Domain\ContactList\ContactListId;
use Grisendo\DDD\DomainException;

class ContactNotFoundException extends DomainException
{
    private ContactListId $listId;

    private ContactEmail $email;

    public function __construct(ContactListId $listId, ContactEmail $email)
    {
        $this->listId = $listId;
        $this->email = $email;
        parent::__construct();
    }

    public function getErrorCode(): string
    {
        return 'contact_not_found';
    }

    protected function getErrorMessage(): string
    {
        return sprintf(
            'The contact for list <%s> with email <%s> does not exist',
            $this->listId->getValue(),
            $this->email->getValue()
        );
    }
}
