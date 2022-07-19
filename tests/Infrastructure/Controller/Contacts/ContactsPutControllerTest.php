<?php

namespace App\Tests\Infrastructure\Controller\Contacts;

use App\Tests\BaseWebTestCase;
use App\Tests\Domain\Contact\ContactEmailMother;
use App\Tests\Domain\Contact\ContactMother;
use App\Tests\Domain\Contact\ContactTokenMother;
use App\Tests\Domain\ContactList\ContactListIdMother;
use Symfony\Component\HttpFoundation\Response;

class ContactsPutControllerTest extends BaseWebTestCase
{
    public function testBadRequest(): void
    {
        $client = $this->getClient();

        $client->jsonRequest('PUT', '/contacts/', [
            'list_id' => ContactListIdMother::random()->getValue()->getValue(),
            'email' => ContactEmailMother::random()->getValue(),
        ]);

        $this->assertIsJSONResponse($client->getResponse());
        $this->assertEquals(
            Response::HTTP_BAD_REQUEST,
            $client->getResponse()->getStatusCode()
        );
        $this->assertIsSymfonyErrorList($client->getResponse());
        $this->assertHasSymfonyErrorPath($client->getResponse(), '[token]');
    }

    public function testContactNotFound(): void
    {
        $client = $this->getClient();

        $client->jsonRequest('PUT', '/contacts/', [
            'list_id' => ContactListIdMother::random()->getValue()->getValue(),
            'email' => ContactEmailMother::random()->getValue(),
            'token' => ContactTokenMother::random()->getValue(),
        ]);

        $this->assertIsJSONResponse($client->getResponse());
        $this->assertEquals(
            Response::HTTP_NOT_FOUND,
            $client->getResponse()->getStatusCode()
        );
        $this->assertEmpty($client->getResponse()->getContent());
    }

    public function testOk(): void
    {
        $contact = ContactMother::random();
        $this->entityManager->persist($contact);
        $this->entityManager->flush();

        $client = $this->getClient();

        $client->jsonRequest('PUT', '/contacts/', [
            'list_id' => $contact->getListId()->getValue()->getValue(),
            'email' => $contact->getEmail()->getValue(),
            'token' => $contact->getToken()->getValue(),
        ]);

        $this->assertIsJSONResponse($client->getResponse());
        $this->assertEquals(
            Response::HTTP_ACCEPTED,
            $client->getResponse()->getStatusCode()
        );
        $this->assertEmpty($client->getResponse()->getContent());
    }
}
