<?php

namespace App\Tests\Infrastructure\Controller\Contacts;

use App\Tests\BaseWebTestCase;
use App\Tests\Domain\Contact\ContactEmailMother;
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
        $content = json_decode(
            $client->getResponse()->getContent(),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        $this->assertEquals(
            Response::HTTP_BAD_REQUEST,
            $client->getResponse()->getStatusCode()
        );
        $this->assertEquals(
            '[id]',
            $content['violations'][0]['propertyPath']
        );
    }
}
