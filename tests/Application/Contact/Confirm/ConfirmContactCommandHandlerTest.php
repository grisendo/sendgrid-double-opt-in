<?php

declare(strict_types=1);

namespace App\Tests\Application\Contact\Confirm;

use App\Application\Contact\Confirm\ConfirmContactCommandHandler;
use App\Application\Contact\Confirm\ContactConfirmer;
use App\Domain\Contact\ContactConfirmedDomainEvent;
use App\Domain\Contact\ContactEmail;
use App\Domain\Contact\ContactRepository;
use App\Domain\ContactList\ContactListId;
use App\Tests\Domain\Contact\ContactMother;
use Grisendo\DDD\Bus\Event\EventBus;
use Mockery;
use PHPUnit\Framework\TestCase;

class ConfirmContactCommandHandlerTest extends TestCase
{
    public function testConfirmContactCommandHandler(): void
    {
        $dummyContact = ContactMother::random();

        $contactRepository = Mockery::mock(ContactRepository::class);
        $contactRepository
            ->shouldReceive('findByListAndEmail')
            ->once()
            ->withArgs(
                function (ContactListId $listId, ContactEmail $email) use (
                    $dummyContact
                ) {
                    return $dummyContact->getListId()->isEqualsTo($listId)
                        && $dummyContact->getEmail()->isEqualsTo($email);
                }
            )
            ->andReturn($dummyContact);
        $contactRepository
            ->shouldReceive('save')
            ->once()
            ->with($dummyContact);

        $bus = Mockery::mock(EventBus::class);
        $bus
            ->shouldReceive('publish')
            ->once()
            ->withArgs(
                function (ContactConfirmedDomainEvent $event) use (
                    $dummyContact
                ) {
                    return $event->getAggregateId() ===
                        $dummyContact->getId()->getValue()->getValue()
                        &&
                        $event->getListId() ===
                        $dummyContact->getListId()->getValue()->getValue()
                        &&
                        $event->getEmail() ===
                        $dummyContact->getEmail()->getValue();
                }
            );

        $confirmer = new ContactConfirmer($contactRepository, $bus);
        $handler = new ConfirmContactCommandHandler($confirmer);

        $this->assertFalse($dummyContact->isConfirmed());

        $handler->__invoke(
            ConfirmContactCommandMother::fromContact($dummyContact)
        );

        $this->assertTrue($dummyContact->isConfirmed());
    }
}
