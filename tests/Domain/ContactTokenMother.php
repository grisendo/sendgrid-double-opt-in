<?php

declare(strict_types=1);

namespace App\Tests\Domain;

use App\Domain\Contact\ContactToken;
use Faker\Factory;

class ContactTokenMother
{
    public static function random(): ContactToken
    {
        return new ContactToken(Factory::create()->sha256());
    }
}
