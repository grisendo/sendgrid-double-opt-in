<?php

declare(strict_types=1);

namespace App\Domain\Contact;

use App\Domain\ContactList\ContactListId;
use Exception;
use Grisendo\DDD\Aggregate\AggregateRoot;

class Contact extends AggregateRoot
{
    private ContactId $id;

    private ContactListId $listId;

    private ContactEmail $email;

    private ContactToken $token;

    private ?ContactName $name;

    private ?ContactSurname $surname;

    private bool $confirmed;

    public function __construct(
        ContactId $id,
        ContactListId $listId,
        ContactEmail $email,
        ContactToken $token,
        ?ContactName $name = null,
        ?ContactSurname $surname = null,
    ) {
        $this->id = $id;
        $this->listId = $listId;
        $this->email = $email;
        $this->token = $token;
        $this->name = $name;
        $this->surname = $surname;
        $this->confirmed = false;
    }

    /**
     * @throws CannotGenerateContactTokenException
     */
    public static function create(
        ContactId $id,
        ContactListId $listId,
        ContactEmail $email,
        ?ContactName $name = null,
        ?ContactSurname $surname = null,
    ): self {
        $contact = new self(
            $id,
            $listId,
            $email,
            self::generateToken(),
            $name,
            $surname
        );

        $contact->record(
            new ContactCreatedDomainEvent(
                $contact->getId()->getValue()->getValue(),
                $listId->getValue()->getValue(),
                $email->getValue(),
                $name?->getValue(),
                $surname?->getValue(),
            )
        );

        return $contact;
    }

    /**
     * @throws CannotGenerateContactTokenException
     */
    public function regenerateToken(): void
    {
        $this->token = self::generateToken();

        $this->record(
            new ContactRegeneratedTokenDomainEvent(
                $this->getId()->getValue()->getValue(),
                $this->getListId()->getValue()->getValue(),
                $this->getEmail()->getValue(),
                $this->getToken()->getValue(),
            )
        );
    }

    /**
     * @throws CannotGenerateContactTokenException
     */
    public function confirm(): void
    {
        $this->confirmed = true;
        $this->token = self::generateToken();

        $this->record(
            new ContactConfirmedDomainEvent(
                $this->getId()->getValue()->getValue(),
                $this->getListId()->getValue()->getValue(),
                $this->getEmail()->getValue(),
            )
        );
    }

    public function getId(): ContactId
    {
        return $this->id;
    }

    public function getListId(): ContactListId
    {
        return $this->listId;
    }

    public function getEmail(): ContactEmail
    {
        return $this->email;
    }

    public function getToken(): ContactToken
    {
        return $this->token;
    }

    public function getName(): ?ContactName
    {
        return $this->name;
    }

    public function getSurname(): ?ContactSurname
    {
        return $this->surname;
    }

    public function isConfirmed(): bool
    {
        return $this->confirmed;
    }

    /**
     * @throws CannotGenerateContactTokenException
     */
    private static function generateToken(): ContactToken
    {
        try {
            return new ContactToken(hash('sha256', random_bytes(32), false));
        } catch (Exception) {
            throw new CannotGenerateContactTokenException();
        }
    }
}
