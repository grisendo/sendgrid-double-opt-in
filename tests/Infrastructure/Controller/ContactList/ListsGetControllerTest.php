<?php

declare(strict_types=1);

namespace App\Tests\Infrastructure\Controller\ContactList;

use App\Tests\BaseWebTestCase;
use App\Tests\Domain\ContactList\ContactListMother;
use Symfony\Component\HttpFoundation\Response;

class ListsGetControllerTest extends BaseWebTestCase
{
    public function testNonExistingContactList(): void
    {
        $id = ContactListMother::random()->getId()->getValue()->getValue();
        $client = $this->getClient();

        $client->jsonRequest('GET', '/lists/'.$id.'/');

        $this->assertIsJSONResponse($client->getResponse());
        $this->assertEquals(
            Response::HTTP_NOT_FOUND,
            $client->getResponse()->getStatusCode()
        );
        $this->assertEmpty($client->getResponse()->getContent());
    }

    public function testExistingContactList(): void
    {
        $list = ContactListMother::random();
        $this->entityManager->persist($list);
        $this->entityManager->flush();

        $id = $list->getId()->getValue()->getValue();
        $client = $this->getClient();

        $client->jsonRequest('GET', '/lists/'.$id.'/');

        $this->assertIsJSONResponse($client->getResponse());
        $this->assertEquals(
            Response::HTTP_OK,
            $client->getResponse()->getStatusCode()
        );
        $this->assertJSONResponseIs(
            $client->getResponse(),
            [
                'id' => $id,
                'name' => $list->getName()->getValue(),
            ]
        );
    }
}
