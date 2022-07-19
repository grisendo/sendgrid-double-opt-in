<?php

declare(strict_types=1);

namespace App\Tests\Domain\Contact;

use App\Domain\Contact\ContactSurname;
use Faker\Factory;

class ContactSurnameMother
{
    public static function random(): ContactSurname
    {
        return new ContactSurname(Factory::create()->lastName());
    }
}
