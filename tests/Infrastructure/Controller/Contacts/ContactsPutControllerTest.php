<?php

namespace App\Tests\Infrastructure\Controller\Contacts;

use App\Tests\BaseWebTestCase;
use App\Tests\Domain\ContactEmailMother;
use App\Tests\Domain\ContactTokenMother;
use Grisendo\DDD\Uuid;
use Symfony\Component\HttpFoundation\Response;

class ContactsPutControllerTest extends BaseWebTestCase
{
    public function testBadRequest(): void
    {
        $client = $this->getClient();

        $client->jsonRequest('PUT', '/contacts/', [
            'list_id' => Uuid::random()->getValue(),
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
            '[token]',
            $content['violations'][0]['propertyPath']
        );
    }

    public function testContactNotFound(): void
    {
        $client = $this->getClient();

        $client->jsonRequest('PUT', '/contacts/', [
            'list_id' => Uuid::random()->getValue(),
            'email' => ContactEmailMother::random()->getValue(),
            'token' => ContactTokenMother::random()->getValue(),
        ]);

        $this->assertEquals(
            Response::HTTP_NOT_FOUND,
            $client->getResponse()->getStatusCode()
        );
        $this->assertEquals(
            Response::$statusTexts[Response::HTTP_NOT_FOUND],
            json_decode(
                $client->getResponse()->getContent(),
                true,
                512,
                JSON_THROW_ON_ERROR
            )
        );
    }
}
