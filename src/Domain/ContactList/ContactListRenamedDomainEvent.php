<?php

declare(strict_types=1);

namespace App\Domain\ContactList;

use Grisendo\DDD\Bus\Event\DomainEvent;
use JetBrains\PhpStorm\ArrayShape;

final class ContactListRenamedDomainEvent extends DomainEvent
{
    private string $name;

    public function __construct(
        string $id,
        string $name,
        string $eventId = null,
        string $occurredOn = null
    ) {
        parent::__construct($id, $eventId, $occurredOn);
        $this->name = $name;
    }

    public static function getEventName(): string
    {
        return 'contact_list.renamed';
    }

    public static function fromPrimitives(
        string $aggregateId,
        #[ArrayShape([
            'name' => 'string',
        ])]
        array $body,
        string $eventId,
        string $occurredOn
    ): DomainEvent {
        return new self(
            $aggregateId,
            $body['name'],
            $eventId,
            $occurredOn
        );
    }

    #[ArrayShape([
        'name' => 'string',
    ])]
    public function toPrimitives(): array
    {
        return [
            'name' => $this->name,
        ];
    }

    public function getName(): string
    {
        return $this->name;
    }
}
