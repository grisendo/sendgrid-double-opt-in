<?php

declare(strict_types=1);

namespace App\Tests\Domain\Contact;

use App\Domain\Contact\ContactEmail;
use Faker\Factory;

class ContactEmailMother
{
    public static function random(): ContactEmail
    {
        return new ContactEmail(Factory::create()->email());
    }
}
