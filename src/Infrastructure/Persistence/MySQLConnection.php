<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use PDO;

class MySQLConnection
{
    private ?PDO $pdo;

    public function __construct(string $dbDSN)
    {
        preg_match(
            '/^(?P<schema>[a-z]+):\/\/(?P<user>\w+)(:(?P<password>\w+))?@(?P<host>[.\w]+)(:(?P<port>\d+))?\/(?P<database>\w+)$/im',
            $dbDSN,
            $matches
        );
        $dsn = $matches['schema'].'://host='.$matches['host'].';port='.$matches['port'].';dbname='.$matches['database'].';charset=utf8mb4';
        $this->pdo = new PDO($dsn, $matches['user'], $matches['password']);
    }

    public function execute(string $query, array $params = []): void
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute($params);
    }

    public function getOneResultOrNull(
        string $query,
        array $params = []
    ): ?array {
        $stmt = $this->pdo->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute($params);
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return $row;
        }

        return null;
    }

    public function __destruct()
    {
        $this->pdo = null;
    }
}
