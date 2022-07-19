<?php

declare(strict_types=1);

namespace App\Tests\Application\Contacts\Confirm;

use App\Application\Contacts\Confirm\ContactConfirmer;
use App\Domain\Contact\ContactConfirmedDomainEvent;
use App\Domain\Contact\ContactNotFoundException;
use App\Domain\Contact\ContactRepository;
use App\Tests\Domain\Contact\ContactMother;
use App\Tests\Domain\Contact\ContactTokenMother;
use Grisendo\DDD\Bus\Event\EventBus;
use Mockery;
use PHPUnit\Framework\TestCase;

class ContactConfirmerTest extends TestCase
{
    public function testContactNotFound(): void
    {
        $dummyContact = ContactMother::random();

        $contactRepository = Mockery::mock(ContactRepository::class);
        $contactRepository
            ->shouldReceive('findByListAndEmail')
            ->once()
            ->with($dummyContact->getListId(), $dummyContact->getEmail())
            ->andReturn(null);
        $contactRepository->shouldNotReceive('save');

        $bus = Mockery::mock(EventBus::class);
        $bus->shouldNotReceive('publish');

        $this->expectException(ContactNotFoundException::class);

        $contactCreator = new ContactConfirmer($contactRepository, $bus);
        $contactCreator->__invoke(
            $dummyContact->getListId(),
            $dummyContact->getEmail(),
            $dummyContact->getToken(),
        );

        $this->assertFalse($dummyContact->isConfirmed());
    }

    public function testInvalidToken(): void
    {
        $dummyContact = ContactMother::random();

        $contactRepository = Mockery::mock(ContactRepository::class);
        $contactRepository
            ->shouldReceive('findByListAndEmail')
            ->once()
            ->with($dummyContact->getListId(), $dummyContact->getEmail())
            ->andReturn($dummyContact);
        $contactRepository->shouldNotReceive('save');

        $bus = Mockery::mock(EventBus::class);
        $bus->shouldNotReceive('publish');

        $this->expectException(ContactNotFoundException::class);

        $contactCreator = new ContactConfirmer($contactRepository, $bus);
        $contactCreator->__invoke(
            $dummyContact->getListId(),
            $dummyContact->getEmail(),
            ContactTokenMother::random(),
        );

        $this->assertFalse($dummyContact->isConfirmed());
    }

    public function testConfirmContact(): void
    {
        $dummyContact = ContactMother::random();
        $oldDummyToken = $dummyContact->getToken();

        $contactRepository = Mockery::mock(ContactRepository::class);
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
            ->with(ContactConfirmedDomainEvent::class);

        $contactCreator = new ContactConfirmer($contactRepository, $bus);
        $contactCreator->__invoke(
            $dummyContact->getListId(),
            $dummyContact->getEmail(),
            $dummyContact->getToken(),
        );

        $this->assertNotEquals($oldDummyToken, $dummyContact->getToken());
        $this->assertTrue($dummyContact->isConfirmed());
    }
}
