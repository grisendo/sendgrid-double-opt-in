<?php

declare(strict_types=1);

namespace App\Tests\Application\Contacts\Confirm;

use App\Application\Contacts\Confirm\ConfirmContactCommand;
use App\Domain\Contact\Contact;
use App\Tests\Domain\ContactEmailMother;
use App\Tests\Domain\ContactTokenMother;
use Grisendo\DDD\Uuid;

class ConfirmContactCommandMother
{
    public static function random(): ConfirmContactCommand
    {
        return new ConfirmContactCommand(
            Uuid::random()->getValue(),
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
