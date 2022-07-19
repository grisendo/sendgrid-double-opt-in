<?php

declare(strict_types=1);

namespace App\Tests\Application\ContactList\Get;

use App\Application\ContactList\Get\ContactListGetter;
use App\Domain\ContactList\ContactListNotFoundException;
use App\Domain\ContactList\ContactListRepository;
use App\Tests\Domain\ContactList\ContactListMother;
use Mockery;
use PHPUnit\Framework\TestCase;

class ContactListGetterTest extends TestCase
{
    public function testNonExisting(): void
    {
        $dummyContactList = ContactListMother::random();

        $contactListRepository = Mockery::mock(ContactListRepository::class);
        $contactListRepository
            ->shouldReceive('findById')
            ->once()
            ->with($dummyContactList->getId())
            ->andReturn(null);

        $this->expectException(ContactListNotFoundException::class);

        $contactListGetter = new ContactListGetter($contactListRepository);

        $contactListGetter->__invoke(
            $dummyContactList->getId()
        );
    }

    public function testExisting(): void
    {
        $dummyContactList = ContactListMother::random();

        $contactListRepository = Mockery::mock(ContactListRepository::class);
        $contactListRepository
            ->shouldReceive('findById')
            ->once()
            ->with($dummyContactList->getId())
            ->andReturn($dummyContactList);

        $contactListGetter = new ContactListGetter($contactListRepository);

        $response = $contactListGetter->__invoke(
            $dummyContactList->getId()
        );

        $this->assertEquals(
            $dummyContactList->getId()->getValue()->getValue(),
            $response->getId()
        );
        $this->assertEquals(
            $dummyContactList->getName()->getValue(),
            $response->getName()
        );
    }
}
