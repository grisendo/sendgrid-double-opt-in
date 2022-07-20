<?php

namespace App\Tests\Application\ContactList\Import;

use App\Application\ContactList\Import\ContactListImporter;
use App\Domain\ContactList\ContactList;
use App\Domain\ContactList\ContactListCreatedDomainEvent;
use App\Domain\ContactList\ContactListRenamedDomainEvent;
use App\Domain\ContactList\ContactListRepository;
use App\Tests\Domain\ContactList\ContactListMother;
use Grisendo\DDD\Bus\Event\EventBus;
use Mockery;
use PHPUnit\Framework\TestCase;

class ContactListImporterTest extends TestCase
{
    public function testEmptyRemoteList(): void
    {
        $remoteRepository = Mockery::mock(ContactListRepository::class);
        $remoteRepository
            ->shouldReceive('findAll')
            ->once()
            ->with()
            ->andReturn([]);

        $localRepository = Mockery::mock(ContactListRepository::class);
        $localRepository
            ->shouldReceive('findById')
            ->never();
        $localRepository
            ->shouldReceive('save')
            ->never();

        $bus = Mockery::mock(EventBus::class);
        $bus
            ->shouldReceive('publish')
            ->never();

        $importer = new ContactListImporter(
            $localRepository,
            $remoteRepository,
            $bus
        );
        $importer->__invoke();

        $this->assertTrue(true);
    }

    public function testNonExistingContactList(): void
    {
        $dummyContactList = ContactListMother::random();

        $remoteRepository = Mockery::mock(ContactListRepository::class);
        $remoteRepository->shouldReceive('findAll')
            ->once()
            ->with()
            ->andReturn([$dummyContactList]);

        $localRepository = Mockery::mock(ContactListRepository::class);
        $localRepository
            ->shouldReceive('findById')
            ->once()
            ->with($dummyContactList->getId())
            ->andReturn(null);

        $localRepository
            ->shouldReceive('save')
            ->once()
            ->withArgs(function (ContactList $list) use ($dummyContactList) {
                return $list->getId()->isEqualsTo($dummyContactList->getId());
            });

        $bus = Mockery::mock(EventBus::class);
        $bus
            ->shouldReceive('publish')
            ->once()
            ->with(ContactListCreatedDomainEvent::class);

        $importer = new ContactListImporter(
            $localRepository,
            $remoteRepository,
            $bus
        );
        $importer->__invoke();

        $this->assertTrue(true);
    }

    public function testExistingContactList(): void
    {
        $localContactList = ContactListMother::random();
        $oldName = $localContactList->getName();
        $remoteContactList = ContactListMother::randomRenaming(
            $localContactList
        );

        $remoteRepository = Mockery::mock(ContactListRepository::class);
        $remoteRepository->shouldReceive('findAll')
            ->once()
            ->with()
            ->andReturn([$remoteContactList]);

        $localRepository = Mockery::mock(ContactListRepository::class);
        $localRepository
            ->shouldReceive('findById')
            ->once()
            ->with($remoteContactList->getId())
            ->andReturn($localContactList);

        $localRepository
            ->shouldReceive('save')
            ->once()
            ->withArgs(function (ContactList $list) use ($localContactList) {
                return $list->getId()->isEqualsTo($localContactList->getId());
            });

        $bus = Mockery::mock(EventBus::class);
        $bus
            ->shouldReceive('publish')
            ->once()
            ->with(ContactListRenamedDomainEvent::class);

        $importer = new ContactListImporter(
            $localRepository,
            $remoteRepository,
            $bus
        );
        $importer->__invoke();

        $this->assertNotEquals(
            $oldName->getValue(),
            $remoteContactList->getName()->getValue()
        );
    }

    public function testExistingContactListButNotUpdated(): void
    {
        $localContactList = ContactListMother::random();
        $oldName = $localContactList->getName();
        $remoteContactList = ContactListMother::fromContactList(
            $localContactList
        );

        $remoteRepository = Mockery::mock(ContactListRepository::class);
        $remoteRepository->shouldReceive('findAll')
            ->once()
            ->with()
            ->andReturn([$remoteContactList]);

        $localRepository = Mockery::mock(ContactListRepository::class);
        $localRepository
            ->shouldReceive('findById')
            ->once()
            ->with($remoteContactList->getId())
            ->andReturn($localContactList);

        $localRepository
            ->shouldReceive('save')
            ->once()
            ->withArgs(function (ContactList $list) use ($localContactList) {
                return $list->getId()->isEqualsTo($localContactList->getId());
            });

        $bus = Mockery::mock(EventBus::class);
        $bus
            ->shouldNotReceive('publish')
            ->never();

        $importer = new ContactListImporter(
            $localRepository,
            $remoteRepository,
            $bus
        );
        $importer->__invoke();

        $this->assertEquals(
            $oldName->getValue(),
            $remoteContactList->getName()->getValue()
        );
    }
}
