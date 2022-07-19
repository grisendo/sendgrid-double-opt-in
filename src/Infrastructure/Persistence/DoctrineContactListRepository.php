<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use App\Domain\ContactList\ContactList;
use App\Domain\ContactList\ContactListId;
use App\Domain\ContactList\ContactListRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final class DoctrineContactListRepository implements ContactListRepository
{
    private EntityManagerInterface $entityManager;

    private ?EntityRepository $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findById(ContactListId $id): ?ContactList
    {
        return $this->getRepository()->find($id);
    }

    public function findAll(): array
    {
        return $this->getRepository()->findBy([], ['name.value' => 'ASC']);
    }

    public function save(ContactList $contactList): void
    {
        $this->entityManager->persist($contactList);
        $this->entityManager->flush();
    }

    private function getRepository(): EntityRepository
    {
        if (!isset($this->repository)) {
            $this->repository = $this->entityManager->getRepository(
                ContactList::class
            );
        }

        return $this->repository;
    }
}
