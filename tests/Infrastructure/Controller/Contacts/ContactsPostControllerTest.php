<?php

namespace App\Tests\Infrastructure\Controller\Contacts;

use App\Tests\BaseWebTestCase;
use App\Tests\Domain\Contact\ContactEmailMother;
use App\Tests\Domain\Contact\ContactIdMother;
use App\Tests\Domain\ContactList\ContactListIdMother;
use Symfony\Component\HttpFoundation\Response;

class ContactsPostControllerTest extends BaseWebTestCase
{
    public function testBadRequest(): void
    {
        $client = $this->getClient();

        $client->jsonRequest('POST', '/contacts/', [
            'list_id' => ContactListIdMother::random()->getValue()->getValue(),
            'email' => ContactEmailMother::random()->getValue(),
        ]);

        $this->assertIsJSONResponse($client->getResponse());
        $this->assertEquals(
            Response::HTTP_BAD_REQUEST,
            $client->getResponse()->getStatusCode()
        );
        $this->assertHasSymfonyErrorPath($client->getResponse(), '[id]');
    }

    public function testContactCreated(): void
    {
        $client = $this->getClient();

        $client->jsonRequest('POST', '/contacts/', [
            'id' => ContactIdMother::random()->getValue()->getValue(),
            'list_id' => ContactListIdMother::random()->getValue()->getValue(),
            'email' => ContactEmailMother::random()->getValue(),
        ]);

        $this->assertIsJSONResponse($client->getResponse());
        $this->assertEquals(
            Response::HTTP_ACCEPTED,
            $client->getResponse()->getStatusCode()
        );
        $this->assertEmpty($client->getResponse()->getContent());
    }
}
