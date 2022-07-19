<?php

declare(strict_types=1);

namespace App\Tests\Application\Contact\Create;

use App\Application\Contact\Create\CreateContactCommand;
use App\Domain\Contact\Contact;
use App\Tests\Domain\Contact\ContactEmailMother;
use App\Tests\Domain\Contact\ContactIdMother;
use App\Tests\Domain\Contact\ContactNameMother;
use App\Tests\Domain\Contact\ContactSurnameMother;
use App\Tests\Domain\ContactList\ContactListIdMother;

class CreateContactCommandMother
{
    public static function random(): CreateContactCommand
    {
        return new CreateContactCommand(
            ContactIdMother::random()->getValue()->getValue(),
            ContactListIdMother::random()->getValue()->getValue(),
            ContactEmailMother::random()->getValue(),
            ContactNameMother::random()->getValue(),
            ContactSurnameMother::random()->getValue(),
        );
    }

    public static function fromContact(Contact $contact): CreateContactCommand
    {
        return new CreateContactCommand(
            $contact->getId()->getValue()->getValue(),
            $contact->getListId()->getValue()->getValue(),
            $contact->getEmail()->getValue(),
            $contact->getName()?->getValue(),
            $contact->getSurname()?->getValue(),
        );
    }
}
