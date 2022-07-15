<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use App\Domain\Contact\Contact;
use App\Domain\Contact\ContactEmail;
use App\Domain\Contact\ContactId;
use App\Domain\Contact\ContactName;
use App\Domain\Contact\ContactRepository;
use App\Domain\Contact\ContactSurname;
use App\Domain\List\ListId;
use Grisendo\DDD\Uuid;

class MySQLContactRepository implements ContactRepository
{
    protected MySQLConnection $connection;

    public function __construct(MySQLConnection $connection)
    {
        $this->connection = $connection;
    }

    public function findById(ContactId $contactId): ?Contact
    {
        $res = $this->connection->getOneResultOrNull(
            'SELECT * FROM contacts WHERE id = :id',
            [
                'id' => $contactId->getValue()->getValue(),
            ]
        );

        if (!$res) {
            return null;
        }

        return new Contact(
            new ContactId(new Uuid($res['id'])),
            new ListId(new Uuid($res['list_id'])),
            new ContactEmail($res['email']),
            new ContactName($res['name']),
            new ContactSurname($res['surname']),
        );
    }

    public function save(Contact $contact): void
    {
        $this->connection->execute(
            'INSERT INTO contacts (id, list_id, email, name, surname) VALUES (:id, :listId, :email, :name, :surname)',
            [
                'id' => $contact->getId()->getValue()->getValue(),
                'listId' => $contact->getListId()->getValue(),
                'email' => $contact->getEmail()->getValue(),
                'name' => $contact->getName()->getValue(),
                'surname' => $contact->getSurname()->getValue(),
            ]
        );
    }
}
