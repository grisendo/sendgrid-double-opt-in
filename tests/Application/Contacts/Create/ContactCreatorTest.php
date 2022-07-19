<?php

declare(strict_types=1);

namespace App\Tests\Application\Contacts\Create;

use App\Application\Contacts\Create\ContactCreator;
use App\Domain\Contact\ContactCreatedDomainEvent;
use App\Domain\Contact\ContactRegeneratedTokenDomainEvent;
use App\Domain\Contact\ContactRepository;
use App\Tests\Domain\Contact\ContactMother;
use Grisendo\DDD\Bus\Event\EventBus;
use Mockery;
use PHPUnit\Framework\TestCase;

class ContactCreatorTest extends TestCase
{
    public function testIdempotence(): void
    {
        $dummyContact = ContactMother::random();
        $oldDummyToken = $dummyContact->getToken();

        $contactRepository = Mockery::mock(ContactRepository::class);
        $contactRepository
            ->shouldReceive('findById')
            ->once()
            ->with($dummyContact->getId())
            ->andReturn($dummyContact);
        $contactRepository->shouldNotReceive('findByListAndEmail');
        $contactRepository->shouldNotReceive('save');

        $bus = Mockery::mock(EventBus::class);
        $bus->shouldNotReceive('publish');

        $contactCreator = new ContactCreator($contactRepository, $bus);
        $contactCreator->__invoke(
            $dummyContact->getId(),
            $dummyContact->getListId(),
            $dummyContact->getEmail(),
            $dummyContact->getName(),
            $dummyContact->getSurname()
        );

        $this->assertEquals($oldDummyToken, $dummyContact->getToken());
    }

    public function testRegenerateToken(): void
    {
        $dummyContact = ContactMother::random();
        $oldDummyToken = $dummyContact->getToken();

        $contactRepository = Mockery::mock(ContactRepository::class);
        $contactRepository
            ->shouldReceive('findById')
            ->once()
            ->with($dummyContact->getId())
            ->andReturn(null);
        $contactRepository
            ->shouldReceive('findByListAndEmail')
            ->once()
            ->with($dummyContact->getListId(), $dummyContact->getEmail())
            ->andReturn($dummyContact);
        $contactRepository
            ->shouldReceive('save')
            ->once()
            ->with($dummyContact);

        $bus = Mockery::mock(EventBus::class);
        $bus
            ->shouldReceive('publish')
            ->once()
            ->with(ContactRegeneratedTokenDomainEvent::class);

        $contactCreator = new ContactCreator($contactRepository, $bus);
        $contactCreator->__invoke(
            $dummyContact->getId(),
            $dummyContact->getListId(),
            $dummyContact->getEmail(),
            $dummyContact->getName(),
            $dummyContact->getSurname()
        );

        $this->assertNotEquals($oldDummyToken, $dummyContact->getToken());
    }

    public function testCreateContact(): void
    {
        $dummyContact = ContactMother::random();

        $contactRepository = Mockery::mock(ContactRepository::class);
        $contactRepository
            ->shouldReceive('findById')
            ->once()
            ->with($dummyContact->getId())
            ->andReturn(null);
        $contactRepository
            ->shouldReceive('findByListAndEmail')
            ->once()
            ->with($dummyContact->getListId(), $dummyContact->getEmail())
            ->andReturn(null);
        $contactRepository
            ->shouldReceive('save')
            ->once();

        $bus = Mockery::mock(EventBus::class);
        $bus
            ->shouldReceive('publish')
            ->once()
            ->with(ContactCreatedDomainEvent::class);

        $contactCreator = new ContactCreator($contactRepository, $bus);
        $contactCreator->__invoke(
            $dummyContact->getId(),
            $dummyContact->getListId(),
            $dummyContact->getEmail(),
            $dummyContact->getName(),
            $dummyContact->getSurname()
        );

        $this->assertFalse($dummyContact->isConfirmed());
    }
}
