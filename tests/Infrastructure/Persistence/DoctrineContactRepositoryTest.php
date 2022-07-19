<?php

namespace App\Tests\Infrastructure\Persistence;

use App\Infrastructure\Persistence\DoctrineContactRepository;
use App\Tests\BaseKernelTestCase;
use App\Tests\Domain\Contact\ContactEmailMother;
use App\Tests\Domain\Contact\ContactIdMother;
use App\Tests\Domain\Contact\ContactMother;
use App\Tests\Domain\ContactList\ContactListIdMother;

class DoctrineContactRepositoryTest extends BaseKernelTestCase
{
    public function testSave(): void
    {
        $contact = ContactMother::random();
        $repository = new DoctrineContactRepository($this->entityManager);

        $repository->save($contact);

        $this->assertTrue(true);
    }

    public function testFindByNonExistingId(): void
    {
        $repository = new DoctrineContactRepository($this->entityManager);

        $contact = $repository->findById(ContactIdMother::random());

        $this->assertNull($contact);
    }

    public function testFindById(): void
    {
        $storedContact = ContactMother::random();
        $id = $storedContact->getId();
        $this->entityManager->persist($storedContact);
        $this->entityManager->flush();
        $repository = new DoctrineContactRepository($this->entityManager);

        $foundContact = $repository->findById($id);

        $this->assertNotNull($foundContact);
        $this->assertEquals($id, $foundContact->getId());
    }

    public function testFindByNonExistingListAndEmail(): void
    {
        $repository = new DoctrineContactRepository($this->entityManager);

        $contact = $repository->findByListAndEmail(
            ContactListIdMother::random(),
            ContactEmailMother::random()
        );

        $this->assertNull($contact);
    }

    public function testFindByListAndEmail(): void
    {
        $storedContact = ContactMother::random();
        $listId = $storedContact->getListId();
        $email = $storedContact->getEmail();
        $this->entityManager->persist($storedContact);
        $this->entityManager->flush();
        $repository = new DoctrineContactRepository($this->entityManager);

        $foundContact = $repository->findByListAndEmail($listId, $email);

        $this->assertNotNull($foundContact);
        $this->assertEquals($listId, $foundContact->getListId());
        $this->assertEquals($email, $foundContact->getEmail());
    }
}
