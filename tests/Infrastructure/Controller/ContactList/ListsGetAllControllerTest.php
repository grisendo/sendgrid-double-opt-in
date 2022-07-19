<?php

declare(strict_types=1);

namespace App\Tests\Infrastructure\Controller\ContactList;

use App\Tests\BaseWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ListsGetAllControllerTest extends BaseWebTestCase
{
    public function testEmptyList(): void
    {
        $client = $this->getClient();

        $client->jsonRequest('GET', '/lists/');

        $this->assertIsJSONResponse($client->getResponse());
        $this->assertEquals(
            Response::HTTP_OK,
            $client->getResponse()->getStatusCode()
        );
        $this->assertJSONResponseIs($client->getResponse(), []);
    }
}
