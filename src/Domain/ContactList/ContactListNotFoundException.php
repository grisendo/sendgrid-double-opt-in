<?php

declare(strict_types=1);

namespace App\Domain\ContactList;

use Grisendo\DDD\DomainException;

class ContactListNotFoundException extends DomainException
{
    private ContactListId $id;

    public function __construct(ContactListId $id)
    {
        $this->id = $id;
        parent::__construct();
    }

    public function getErrorCode(): string
    {
        return 'contact_list_not_found';
    }

    protected function getErrorMessage(): string
    {
        return sprintf(
            'The contact list <%s> does not exist',
            $this->id->getValue()->getValue(),
        );
    }
}
