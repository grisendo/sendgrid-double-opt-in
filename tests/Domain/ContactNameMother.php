<?php

declare(strict_types=1);

namespace App\Tests\Domain;

use App\Domain\Contact\ContactName;
use Faker\Factory;

class ContactNameMother
{
    public static function random(): ContactName
    {
        return new ContactName(Factory::create()->firstName());
    }
}
