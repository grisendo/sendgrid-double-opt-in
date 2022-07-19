<?php

declare(strict_types=1);

namespace App\Domain\Contact;

use Grisendo\DDD\Bus\Event\DomainEvent;
use JetBrains\PhpStorm\ArrayShape;

final class ContactConfirmedDomainEvent extends DomainEvent
{
    private string $listId;

    private string $email;

    public function __construct(
        string $id,
        string $listId,
        string $email,
        string $eventId = null,
        string $occurredOn = null
    ) {
        parent::__construct($id, $eventId, $occurredOn);
        $this->listId = $listId;
        $this->email = $email;
    }

    public static function getEventName(): string
    {
        return 'contact.confirmed';
    }

    public static function fromPrimitives(
        string $aggregateId,
        #[ArrayShape([
            'list_id' => 'string',
            'email' => 'string',
        ])]
        array $body,
        string $eventId,
        string $occurredOn
    ): DomainEvent {
        return new self(
            $aggregateId,
            $body['list_id'],
            $body['email'],
            $eventId,
            $occurredOn
        );
    }

    #[ArrayShape([
        'list_id' => 'string',
        'email' => 'string',
    ])]
    public function toPrimitives(): array
    {
        return [
            'list_id' => $this->listId,
            'email' => $this->email,
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
}
