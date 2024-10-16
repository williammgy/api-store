<?php declare(strict_types=1);

namespace App\Application\Shop;

use App\Domain\Shop\ShopRepositoryInterface;

class ListShopsHandler
{
    public function __construct(
        private ShopRepositoryInterface $shopRepository
    ) {}

    public function handle(array $filters): array
    {
        return $this->shopRepository->findAll($filters);
    }
}