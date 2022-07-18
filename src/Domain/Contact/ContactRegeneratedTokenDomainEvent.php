<?php

declare(strict_types=1);

namespace App\Domain\Contact;

use Grisendo\DDD\Bus\Event\DomainEvent;
use JetBrains\PhpStorm\ArrayShape;

final class ContactRegeneratedTokenDomainEvent extends DomainEvent
{
    private string $listId;

    private string $email;

    private ?string $token;

    public function __construct(
        string $id,
        string $token,
        string $listId,
        string $email,
        string $eventId = null,
        string $occurredOn = null
    ) {
        parent::__construct($id, $eventId, $occurredOn);
        $this->listId = $listId;
        $this->email = $email;
        $this->token = $token;
    }

    public static function getEventName(): string
    {
        return 'contact.regenerated_token';
    }

    public static function fromPrimitives(
        string $aggregateId,
        #[ArrayShape([
            'list_id' => 'string',
            'email' => 'string',
            'token' => 'string',
        ])]
        array $body,
        string $eventId,
        string $occurredOn
    ): DomainEvent {
        return new self(
            $aggregateId,
            $body['list_id'],
            $body['email'],
            $body['token'],
            $eventId,
            $occurredOn
        );
    }

    #[ArrayShape([
        'list_id' => 'string',
        'email' => 'string',
        'token' => 'string',
    ])]
    public function toPrimitives(): array
    {
        return [
            'list_id' => $this->listId,
            'email' => $this->email,
            'token' => $this->token,
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

    public function getToken(): string
    {
        return $this->token;
    }
}
