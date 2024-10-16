<?php declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use App\Domain\Shop\Shop;
use App\Domain\Shop\ShopRepositoryInterface;
use App\Domain\Time\Time;
use App\Domain\Type\TypeRepositoryInterface;
use App\Infrastructure\Database\DatabaseConnection;

use PDO;

class PDOShopRepository implements ShopRepositoryInterface
{
    private PDO $connection;

    public function __construct(
        DatabaseConnection $dbConnection,
        private TypeRepositoryInterface $typeRepository
    ) {
        $this->connection = $dbConnection->getConnection();
    }

    public function findAll(array $filters = []): array
    {
        $query = '
            SELECT
                s.id as s_id, s.name, s.slogan, t.id as t_id, t.label, s.address, s.opening_hour, s.closing_hour
            FROM
                shop s
            LEFT JOIN
                type t
            ON
                t.id = s.type_id
            WHERE
                1=1
        ';

        if (isset($filters['name'])) $query .= ' AND s.name LIKE :name';
        if (isset($filters['type'])) $query .= ' AND t.label LIKE :type';
        if (isset($filters['address'])) $query .= ' AND s.address LIKE :address';

        if (isset($filters['sort'])) {
            $order = isset($filters['order']) && in_array(strtoupper($filters['order']), ['ASC', 'DESC']) ?
                strtoupper($filters['order']) :
                'ASC';

            $query .= ' ORDER BY s.' . $filters['sort'] . ' ' . $order;
        };

        $stmt = $this->connection->prepare($query);

        if (isset($filters['name'])) $stmt->bindValue(':name', '%' . $filters['name'] . '%');
        if (isset($filters['type'])) $stmt->bindValue(':type', '%' . $filters['type'] . '%');
        if (isset($filters['address'])) $stmt->bindValue(':address', $filters['address']);

        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $shops = [];

        foreach ($data as $shop) {
            $openingHour = explode(':', $shop['opening_hour']);
            $closingHour = explode(':', $shop['closing_hour']);

            $shops[] = new Shop(
                $shop['name'],
                $this->typeRepository->findById($shop['t_id']),
                $shop['address'],
                new Time(
                    (int) $openingHour[0],
                    (int) $openingHour[1],
                    (int) $openingHour[2]
                ),
                new Time(
                    (int) $closingHour[0],
                    (int) $closingHour[1],
                    (int) $closingHour[2]
                ),
                $shop['slogan'],
                $shop['s_id']
            );
        }

        return $shops;
    }

    public function findById(string $id): ?Shop
    {
        $stmt = $this->connection->prepare('
            SELECT
                s.id as s_id, s.name, s.slogan, t.id as t_id, t.label, s.address, s.opening_hour, s.closing_hour
            FROM
                shop s
            LEFT JOIN
                type t
            ON
                t.id = s.type_id
            WHERE
                s.id = :id
        ');

        $stmt->bindValue(':id', $id);

        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data === false) return null;

        $type = $this->typeRepository->findById($data['t_id']);

        $openingHour = explode(':', $data['opening_hour']);
        $closingHour = explode(':', $data['closing_hour']);

        return new Shop(
            $data['name'],
            $type,
            $data['address'],
            new Time(
                (int) $openingHour[0],
                (int) $openingHour[1],
                (int) $openingHour[2]
            ),
            new Time(
                (int) $closingHour[0],
                (int) $closingHour[1],
                (int) $closingHour[2]
            ),
            $data['slogan'],
            $data['s_id']
        );
    }

    public function findByName(string $name): ?Shop
    {
        $name = "%$name%";

        $stmt = $this->connection->prepare('
            SELECT
                s.id as s_id, s.name, s.slogan, t.id as t_id, t.label, s.address, s.opening_hour, s.closing_hour
            FROM
                shop s
            LEFT JOIN
                type t
            ON
                t.id = s.type_id
            WHERE
                s.name LIKE :name
        ');

        $stmt->bindValue(':name', $name);

        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data === false) return null;

        $type = $this->typeRepository->findById($data['t_id']);

        $openingHour = explode(':', $data['opening_hour']);
        $closingHour = explode(':', $data['closing_hour']);

        return new Shop(
            $data['name'],
            $type,
            $data['address'],
            new Time(
                (int) $openingHour[0],
                (int) $openingHour[1],
                (int) $openingHour[2]
            ),
            new Time(
                (int) $closingHour[0],
                (int) $closingHour[1],
                (int) $closingHour[2]
            ),
            $data['slogan'],
            $data['s_id']
        );
    }

    public function insert(Shop $shop): void
    {
        $id = $shop->getId();
        $name = $shop->getName();
        $slogan = $shop->getSlogan();
        $type = $shop->getType()->getId();
        $address = $shop->getAddress();
        $openingHour = $shop->getOpeningHour()->__toString();
        $closingHour = $shop->getClosingHour()->__toString();

        $stmt = $this->connection->prepare('
            INSERT INTO shop (
                id, name, slogan, type_id, address, opening_hour, closing_hour
            ) VALUES (
                :id, :name, :slogan, :type, :address, :opening_hour, :closing_hour
            )
        ');

        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':slogan', $slogan);
        $stmt->bindValue(':type', $type);
        $stmt->bindValue(':address', $address);
        $stmt->bindValue(':opening_hour', $openingHour);
        $stmt->bindValue(':closing_hour', $closingHour);

        $stmt->execute();
    }

    public function update(Shop $shop): void
    {
        $id = $shop->getId();
        $name = $shop->getName();
        $slogan = $shop->getSlogan();
        $type = $shop->getType()->getId();
        $address = $shop->getAddress();
        $openingHour = $shop->getOpeningHour()->__toString();
        $closingHour = $shop->getClosingHour()->__toString();

        $stmt = $this->connection->prepare('
            UPDATE shop SET
                name         = :name,
                slogan       = :slogan,
                type_id      = :type,
                address      = :address,
                opening_hour = :opening_hour,
                closing_hour = :closing_hour
            WHERE
                id = :id
        ');

        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':slogan', $slogan);
        $stmt->bindValue(':type', $type);
        $stmt->bindValue(':address', $address);
        $stmt->bindValue(':opening_hour', $openingHour);
        $stmt->bindValue(':closing_hour', $closingHour);

        $stmt->execute();
    }

    public function delete(string $id): void
    {
        $stmt = $this->connection->prepare('
            DELETE FROM
                shop s
            WHERE
                s.id = :id
        ');

        $stmt->bindValue(':id', $id);

        $stmt->execute();
    }
}