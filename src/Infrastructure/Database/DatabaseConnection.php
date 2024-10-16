<?php declare(strict_types=1);

namespace App\Infrastructure\Database;

use PDO;
use PDOException;

class DatabaseConnection
{
    private PDO $connection;

    public function __construct(
        string $host,
        string $name,
        string $user,
        string $password
    ) {
        try {
            $dsn = "mysql:host=$host;dbname=$name;charset=utf8mb4";

            $this->connection = new PDO($dsn, $user, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
        } catch (PDOException $e) {
            throw new \RuntimeException($e->getMessage());
        }
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }
}