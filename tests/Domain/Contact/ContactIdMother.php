<?php

declare(strict_types=1);

namespace App\Tests\Domain\Contact;

use App\Domain\Contact\ContactId;
use Grisendo\DDD\Uuid;

class ContactIdMother
{
    public static function random(): ContactId
    {
        return new ContactId(Uuid::random());
    }
}
