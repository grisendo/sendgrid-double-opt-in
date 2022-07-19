<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use App\Domain\Contact\Contact;
use App\Domain\Contact\ContactEmail;
use App\Domain\Contact\ContactId;
use App\Domain\Contact\ContactRepository;
use App\Domain\ContactList\ContactListId;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final class DoctrineContactRepository implements ContactRepository
{
    private EntityManagerInterface $entityManager;

    private ?EntityRepository $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findById(ContactId $id): ?Contact
    {
        return $this->getRepository()->find($id);
    }

    public function findByListAndEmail(
        ContactListId $listId,
        ContactEmail $email
    ): ?Contact {
        return $this->getRepository()->findOneBy([
            'listId' => $listId,
            'email.value' => $email->getValue(),
        ]);
    }

    public function save(Contact $contact): void
    {
        $this->entityManager->persist($contact);
        $this->entityManager->flush();
    }

    private function getRepository(): EntityRepository
    {
        if (!isset($this->repository)) {
            $this->repository = $this->entityManager->getRepository(
                Contact::class
            );
        }

        return $this->repository;
    }
}
