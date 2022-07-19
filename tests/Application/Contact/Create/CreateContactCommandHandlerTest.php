<?php

declare(strict_types=1);

namespace App\Tests\Application\Contact\Create;

use App\Application\Contact\Create\ContactCreator;
use App\Application\Contact\Create\CreateContactCommandHandler;
use App\Domain\Contact\Contact;
use App\Domain\Contact\ContactCreatedDomainEvent;
use App\Domain\Contact\ContactEmail;
use App\Domain\Contact\ContactId;
use App\Domain\Contact\ContactRegeneratedTokenDomainEvent;
use App\Domain\Contact\ContactRepository;
use App\Domain\ContactList\ContactListId;
use App\Tests\Domain\Contact\ContactMother;
use Grisendo\DDD\Bus\Event\EventBus;
use Mockery;
use PHPUnit\Framework\TestCase;

class CreateContactCommandHandlerTest extends TestCase
{
    /**
     * @dataProvider getCases
     */
    public function testCreateContactCommandHandler(
        Contact $dummyContact,
        bool $foundById,
        bool $foundByListAndEmail,
    ): void {
        $contactRepository = Mockery::mock(ContactRepository::class);
        $contactRepository
            ->shouldReceive('findById')
            ->once()
            ->withArgs(
                function (ContactId $id) use ($dummyContact) {
                    return $dummyContact->getId()->isEqualsTo($id);
                }
            )
            ->andReturn($foundById ? $dummyContact : null);
        if ($foundById) {
            $contactRepository
                ->shouldNotReceive('findByListAndEmail');
        } else {
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
                ->andReturn($foundByListAndEmail ? $dummyContact : null);
        }
        if ($foundById) {
            $contactRepository->shouldNotReceive('save');
        } else {
            $contactRepository
                ->shouldReceive('save')
                ->once()
                ->withArgs(function (Contact $contact) use ($dummyContact) {
                    return $dummyContact->getId()->isEqualsTo(
                        $contact->getId()
                    );
                });
        }

        $bus = Mockery::mock(EventBus::class);
        if ($foundById) {
            $bus->shouldNotReceive('publish');
        } elseif ($foundByListAndEmail) {
            $bus
                ->shouldReceive('publish')
                ->once()
                ->withArgs(
                    function (ContactRegeneratedTokenDomainEvent $event) use (
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
        } else {
            $bus
                ->shouldReceive('publish')
                ->once()
                ->withArgs(
                    function (ContactCreatedDomainEvent $event) use (
                        $dummyContact
                    ) {
                        return $event->getAggregateId() ===
                            $dummyContact->getId()->getValue()->getValue()
                            &&
                            $event->getListId() ===
                            $dummyContact->getListId()->getValue()->getValue()
                            &&
                            $event->getEmail() ===
                            $dummyContact->getEmail()->getValue()
                            &&
                            $event->getName() ===
                            $dummyContact->getName()?->getValue()
                            &&
                            $event->getSurname() ===
                            $dummyContact->getSurname()?->getValue();
                    }
                );
        }

        $creator = new ContactCreator($contactRepository, $bus);
        $handler = new CreateContactCommandHandler($creator);

        $handler->__invoke(
            CreateContactCommandMother::fromContact($dummyContact)
        );

        $this->assertFalse($dummyContact->isConfirmed());
    }

    protected function getCases(): array
    {
        return [
            [ContactMother::random(), false, false],
            [ContactMother::random(), false, true],
            [ContactMother::random(), true, false],
            [ContactMother::random(), true, true],
            [ContactMother::unnamedRandom(), false, false],
            [ContactMother::unnamedRandom(), false, true],
            [ContactMother::unnamedRandom(), true, false],
            [ContactMother::unnamedRandom(), true, true],
        ];
    }
}
