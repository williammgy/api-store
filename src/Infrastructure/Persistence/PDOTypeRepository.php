<?php declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use App\Domain\Type\Type;
use App\Domain\Type\TypeRepositoryInterface;
use App\Infrastructure\Database\DatabaseConnection;

use PDO;

class PDOTypeRepository implements TypeRepositoryInterface
{
    private PDO $connection;

    public function __construct(
        DatabaseConnection $dbConnection
    ) {
        $this->connection = $dbConnection->getConnection();
    }

    public function findAll(): array
    {
        $stmt = $this->connection->prepare('
            SELECT
                t.id, t.label
            FROM
                type t
        ');

        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $types = [];

        foreach ($data as $type) {
            $types[] = new Type($type['label'], $type['id']);
        }

        return $types;
    }

    public function findById(int $id): Type|null
    {
        $stmt = $this->connection->prepare('
            SELECT
                t.id, t.label
            FROM
                type t
            WHERE
                t.id = :id
        ');

        $stmt->bindValue(':id', $id);

        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($data)) return null;

        return new Type( $data[0]['label'], $data[0]['id']);
    }
}