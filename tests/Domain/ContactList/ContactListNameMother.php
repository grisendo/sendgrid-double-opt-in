<?php

declare(strict_types=1);

namespace App\Tests\Domain\ContactList;

use App\Domain\ContactList\ContactListName;
use Faker\Factory;

class ContactListNameMother
{
    public static function random(): ContactListName
    {
        return new ContactListName(Factory::create()->name());
    }
}
