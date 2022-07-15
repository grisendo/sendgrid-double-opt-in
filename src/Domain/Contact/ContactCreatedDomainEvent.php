<?php

declare(strict_types=1);

namespace App\Domain\Contact;

use Grisendo\DDD\Bus\Event\DomainEvent;
use JetBrains\PhpStorm\ArrayShape;

final class ContactCreatedDomainEvent extends DomainEvent
{
    private string $listId;

    private string $email;

    private string $name;

    private string $surname;

    public function __construct(
        string $id,
        string $listId,
        string $email,
        string $name,
        string $surname,
        string $eventId = null,
        string $occurredOn = null
    ) {
        parent::__construct($id, $eventId, $occurredOn);
        $this->listId = $listId;
        $this->email = $email;
        $this->name = $name;
        $this->surname = $surname;
    }

    public static function getEventName(): string
    {
        return 'contact.created';
    }

    public static function fromPrimitives(
        string $aggregateId,
        #[ArrayShape([
            'list_id' => 'string',
            'email' => 'string',
            'name' => 'string',
            'surname' => 'string',
        ])]
        array $body,
        string $eventId,
        string $occurredOn
    ): DomainEvent {
        return new self(
            $aggregateId,
            $body['list_id'],
            $body['email'],
            $body['name'],
            $body['surname'],
            $eventId,
            $occurredOn
        );
    }

    #[ArrayShape([
        'list_id' => 'string',
        'email' => 'string',
        'name' => 'string',
        'surname' => 'string',
    ])]
    public function toPrimitives(): array
    {
        return [
            'list_id' => $this->listId,
            'email' => $this->email,
            'name' => $this->name,
            'surname' => $this->surname,
        ];
    }

    public function getListId(): string
    {
        return $this->listId;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }
}
