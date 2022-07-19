<?php

declare(strict_types=1);

namespace App\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Exception;
use LogicException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

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

    protected function isJSONResponse(Response $response): bool
    {
        return 'application/json' === $response->headers->get('Content-Type');
    }

    protected function assertIsJSONResponse(
        Response $response,
        string $message = ''
    ): void {
        $this->assertTrue($this->isJSONResponse($response), $message);
    }

    protected function isSymfonyErrorList(Response $response): bool
    {
        if (!$this->isJSONResponse($response)) {
            return false;
        }

        try {
            $content = json_decode(
                $response->getContent(),
                true,
                512,
                JSON_THROW_ON_ERROR
            );
            if (null === $content) {
                return false;
            }
            if (!isset($content['type']) || 'https://symfony.com/errors/validation' !== $content['type']) {
                return false;
            }

            return !empty($content['violations']);
        } catch (Exception) {
            return false;
        }
    }

    protected function assertIsSymfonyErrorList(
        Response $response,
        string $message = ''
    ): void {
        $this->assertTrue($this->isSymfonyErrorList($response), $message);
    }

    protected function hasSymfonyErrorPath(
        Response $response,
        string $path
    ): bool {
        if (!$this->isSymfonyErrorList($response)) {
            return false;
        }

        $content = json_decode(
            $response->getContent(),
            true,
            512,
            JSON_THROW_ON_ERROR
        );
        foreach ($content['violations'] as $violation) {
            if ($violation['propertyPath'] === $path) {
                return true;
            }
        }

        return false;
    }

    protected function assertHasSymfonyErrorPath(
        Response $response,
        string $path,
        string $message = ''
    ): void {
        $this->assertTrue(
            $this->hasSymfonyErrorPath($response, $path),
            $message
        );
    }

    protected function getResponseContentAsArray(Response $response): ?array
    {
        if (!$this->isJSONResponse($response)) {
            return null;
        }

        $content = json_decode(
            $response->getContent(),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        if (is_array($content)) {
            return $content;
        }

        return null;
    }

    protected function assertJSONResponseIs(
        Response $response,
        array $data,
        string $message = ''
    ): void {
        $content = $this->getResponseContentAsArray($response);
        $this->assertNotNull($content, $message);
        $this->assertEquals($data, $content, $message);
    }
}
