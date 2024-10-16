<?php declare(strict_types=1);

namespace App\Application\Shop;

use App\Domain\Shop\Shop;
use App\Domain\Shop\ShopRepositoryInterface;
use App\Domain\Time\Time;
use App\Domain\Type\TypeRepositoryInterface;

class CreateShopHandler
{
    public function __construct(
        private ShopRepositoryInterface $shopRepository,
        private TypeRepositoryInterface $typeRepository
    ) {}

    public function handle(array $data): void
    {
        $shop = $this->shopRepository->findByName($data['name']);

        if ($shop) throw new \Exception('Shop already existing');

        $type = $this->typeRepository->findById($data['type']);

        if (!$type) throw new \Exception('Type not found');

        $openingHour = explode(':', $data['opening_hour']);
        $closingHour = explode(':', $data['closing_hour']);

        $shop = new Shop(
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
            $data['slogan']
        );

        $this->shopRepository->insert($shop);
    }
}