<?php

declare(strict_types=1);

namespace App\Tests\Application\ContactList\Get;

use App\Application\ContactList\Get\ContactListGetter;
use App\Application\ContactList\Get\GetContactListQueryHandler;
use App\Domain\ContactList\ContactListId;
use App\Domain\ContactList\ContactListNotFoundException;
use App\Domain\ContactList\ContactListRepository;
use App\Tests\Domain\ContactList\ContactListMother;
use Mockery;
use PHPUnit\Framework\TestCase;

class GetContactListQueryHandlerTest extends TestCase
{
    public function testNonExistingContactListQueryHandler(): void
    {
        $dummyContactList = ContactListMother::random();

        $contactListRepository = Mockery::mock(ContactListRepository::class);
        $contactListRepository
            ->shouldReceive('findById')
            ->once()
            ->withArgs(
                function (ContactListId $contactListId) use ($dummyContactList
                ) {
                    return $dummyContactList->getId()->isEqualsTo(
                        $contactListId
                    );
                }
            )
            ->andReturn(null);

        $contactListGetter = new ContactListGetter($contactListRepository);
        $handler = new GetContactListQueryHandler($contactListGetter);

        $this->expectException(ContactListNotFoundException::class);

        $handler->__invoke(
            GetContactListQueryMother::fromContactList($dummyContactList)
        );
    }

    public function testExistingContactListQueryHandler(): void
    {
        $dummyContactList = ContactListMother::random();

        $contactListRepository = Mockery::mock(ContactListRepository::class);
        $contactListRepository
            ->shouldReceive('findById')
            ->once()
            ->withArgs(
                function (ContactListId $contactListId) use ($dummyContactList
                ) {
                    return $dummyContactList->getId()->isEqualsTo(
                        $contactListId
                    );
                }
            )
            ->andReturn($dummyContactList);

        $contactListGetter = new ContactListGetter($contactListRepository);
        $handler = new GetContactListQueryHandler($contactListGetter);
        $response = $handler->__invoke(
            GetContactListQueryMother::fromContactList($dummyContactList)
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
