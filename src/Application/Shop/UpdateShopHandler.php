<?php declare(strict_types=1);

namespace App\Application\Shop;

use App\Domain\Time\Time;
use App\Domain\Shop\ShopRepositoryInterface;
use App\Domain\Type\TypeRepositoryInterface;

class UpdateShopHandler
{
    public function __construct(
        private ShopRepositoryInterface $shopRepository,
        private TypeRepositoryInterface $typeRepository
    ) {}

    public function handle(string $id, array $data): void
    {
        $shop = $this->shopRepository->findById($id);

        if (!$shop) throw new \Exception('Shop not found');

        $type = isset($data['type']) ?
            $this->typeRepository->findById($data['type']) :
            $shop->getType();

        if ($data['type'] && !$type) throw new \Exception('Type not found');

        if (isset($data['opening_hour'])) {
            $time = explode(':', $data['opening_hour']);

            $openingHour = new Time(
                (int) $time[0],
                (int) $time[1],
                (int) $time[2]
            );
        } else {
            $openingHour = $shop->getOpeningHour();
        }

        if (isset($data['closing_hour'])) {
            $time = explode(':', $data['closing_hour']);

            $closingHour = new Time(
                (int) $time[0],
                (int) $time[1],
                (int) $time[2]
            );
        } else {
            $closingHour = $shop->getClosingHour();
        }

        $shop->update(
            $data['name'] ?? $shop->getName(),
            $type,
            $data['address'] ?? $shop->getAddress(),
            $openingHour,
            $closingHour,
            $data['slogan'] ?? $shop->getSlogan()
        );

        $this->shopRepository->update($shop);
    }
}