<?php

declare(strict_types=1);

namespace App\Tests\Application\ContactList\GetAll;

use App\Application\ContactList\GetAll\AllContactListGetter;
use App\Domain\ContactList\ContactListRepository;
use App\Tests\Domain\ContactList\ContactListMother;
use Mockery;
use PHPUnit\Framework\TestCase;

class AllContactListGetterTest extends TestCase
{
    public function testEmptyGetAll(): void
    {
        $contactListRepository = Mockery::mock(ContactListRepository::class);
        $contactListRepository
            ->shouldReceive('findAll')
            ->once()
            ->with()
            ->andReturn([]);

        $allContactListGetter = new AllContactListGetter(
            $contactListRepository
        );

        $response = $allContactListGetter->__invoke();

        $this->assertEquals([], $response->getContactLists());
    }

    public function testGetAll(): void
    {
        $dummyContactList = ContactListMother::random();

        $contactListRepository = Mockery::mock(ContactListRepository::class);
        $contactListRepository
            ->shouldReceive('findAll')
            ->once()
            ->with()
            ->andReturn([$dummyContactList]);

        $allContactListGetter = new AllContactListGetter(
            $contactListRepository
        );

        $response = $allContactListGetter->__invoke();

        $this->assertCount(1, $response->getContactLists());
        $this->assertEquals(
            $dummyContactList->getId()->getValue()->getValue(),
            $response->getContactLists()[0]->getId(),
        );
        $this->assertEquals(
            $dummyContactList->getName()->getValue(),
            $response->getContactLists()[0]->getName(),
        );
    }
}
