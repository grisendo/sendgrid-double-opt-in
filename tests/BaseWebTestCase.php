<?php

declare(strict_types=1);

namespace App\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use LogicException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BaseWebTestCase extends WebTestCase
{
    protected EntityManagerInterface $entityManager;

    protected KernelBrowser $client;

    protected function getClient(): KernelBrowser
    {
        return $this->client;
    }

    protected function setUp(): void
    {
        $this->client = self::createClient();

        if ('test' !== $this->client->getKernel()->getEnvironment()) {
            throw new LogicException();
        }

        $this->entityManager = $this->client->getKernel()->getContainer()
            ->get('doctrine')
            ->getManager();
        $metaData = $this->entityManager
            ->getMetadataFactory()
            ->getAllMetadata();
        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->dropDatabase();
        $schemaTool->updateSchema($metaData);
    }
}
