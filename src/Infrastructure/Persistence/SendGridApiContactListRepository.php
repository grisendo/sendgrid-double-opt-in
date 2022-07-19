<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use App\Domain\ContactList\ContactList;
use App\Domain\ContactList\ContactListId;
use App\Domain\ContactList\ContactListName;
use App\Domain\ContactList\ContactListRepository;
use Grisendo\DDD\Uuid;
use JetBrains\PhpStorm\ArrayShape;
use RuntimeException;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

final class SendGridApiContactListRepository implements ContactListRepository
{
    private HttpClientInterface $client;

    private string $apiKey;

    public function __construct(
        HttpClientInterface $client,
        string $sendGridApiKey
    ) {
        $this->client = $client;
        $this->apiKey = $sendGridApiKey;
    }

    public function findById(ContactListId $id): ?ContactList
    {
        $response = $this->client->request(
            'GET',
            'https://api.sendgrid.com/v3/marketing/lists/'.$id->getValue()
                ->getValue(),
            ['headers' => $this->getHeaders()]
        );

        try {
            if (200 !== $response->getStatusCode()) {
                return null;
            }

            return $this->hydrateContactList($response->toArray());
        } catch (Throwable) {
            return null;
        }
    }

    public function findAll(): array
    {
        $response = $this->client->request(
            'GET',
            'https://api.sendgrid.com/v3/marketing/lists',
            ['headers' => $this->getHeaders()]
        );

        try {
            if (200 !== $response->getStatusCode()) {
                return [];
            }

            return $this->hydrateContactLists($response->toArray()['result']);
        } catch (Throwable) {
            return [];
        }
    }

    /**
     * @throws RuntimeException
     */
    public function save(ContactList $contactList): void
    {
        throw new RuntimeException();
    }

    #[ArrayShape([
        'Content-Type' => 'string',
        'Accept' => 'string',
        'Authorization' => 'string',
    ])]
    private function getHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$this->apiKey,
        ];
    }

    private function hydrateContactList(
        #[ArrayShape([
            'id' => 'string',
            'name' => 'string',
        ])]
        array $line
    ): ContactList {
        return new ContactList(
            new ContactListId(new Uuid($line['id'])),
            new ContactListName($line['name'])
        );
    }

    private function hydrateContactLists(array $lines): array
    {
        return array_map(function (array $line) {
            return $this->hydrateContactList($line);
        }, $lines);
    }
}
