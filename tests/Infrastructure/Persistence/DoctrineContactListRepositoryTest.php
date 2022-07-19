<?php

namespace App\Tests\Infrastructure\Persistence;

use App\Infrastructure\Persistence\DoctrineContactListRepository;
use App\Tests\BaseKernelTestCase;
use App\Tests\Domain\ContactList\ContactListIdMother;
use App\Tests\Domain\ContactList\ContactListMother;

class DoctrineContactListRepositoryTest extends BaseKernelTestCase
{
    public function testFindByNonExistingId(): void
    {
        $repository = new DoctrineContactListRepository($this->entityManager);

        $contactList = $repository->findById(ContactListIdMother::random());

        $this->assertNull($contactList);
    }

    public function testFindById(): void
    {
        $storedList = ContactListMother::random();
        $id = $storedList->getId();
        $this->entityManager->persist($storedList);
        $this->entityManager->flush();
        $repository = new DoctrineContactListRepository($this->entityManager);

        $foundList = $repository->findById($id);

        $this->assertNotNull($foundList);
        $this->assertEquals($id, $foundList->getId());
    }


    public function testFindAllEmpty(): void
    {
        $repository = new DoctrineContactListRepository($this->entityManager);

        $lists = $repository->findAll();

        $this->assertCount(0, $lists);
    }


    public function testFindAll(): void
    {
        $storedList = ContactListMother::random();
        $this->entityManager->persist($storedList);
        $this->entityManager->flush();
        $repository = new DoctrineContactListRepository($this->entityManager);

        $foundLists = $repository->findall();

        $this->assertCount(1, $foundLists);
        $this->assertEquals($storedList, $foundLists[0]);
    }
}
