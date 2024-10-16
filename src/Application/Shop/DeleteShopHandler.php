<?php declare(strict_types=1);

namespace App\Application\Shop;

use App\Domain\Shop\ShopRepositoryInterface;

class DeleteShopHandler
{
    public function __construct(
        private ShopRepositoryInterface $shopRepository
    ) {}

    public function handle(string $id): void
    {
        $shop = $this->shopRepository->findById($id);

        if (!$shop) throw new \Exception('Shop not found');

        $this->shopRepository->delete($id);
    }
}