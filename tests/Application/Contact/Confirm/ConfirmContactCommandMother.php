<?php

declare(strict_types=1);

namespace App\Tests\Application\Contact\Confirm;

use App\Application\Contact\Confirm\ConfirmContactCommand;
use App\Domain\Contact\Contact;
use App\Tests\Domain\Contact\ContactEmailMother;
use App\Tests\Domain\Contact\ContactTokenMother;
use App\Tests\Domain\ContactList\ContactListIdMother;

class ConfirmContactCommandMother
{
    public static function random(): ConfirmContactCommand
    {
        return new ConfirmContactCommand(
            ContactListIdMother::random()->getValue()->getValue(),
            ContactEmailMother::random()->getValue(),
            ContactTokenMother::random()->getValue(),
        );
    }

    public static function fromContact(Contact $contact): ConfirmContactCommand
    {
        return new ConfirmContactCommand(
            $contact->getListId()->getValue()->getValue(),
            $contact->getEmail()->getValue(),
            $contact->getToken()->getValue(),
        );
    }
}
