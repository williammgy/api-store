<?php declare(strict_types=1);

namespace App\Domain\Shop;

interface ShopRepositoryInterface
{
    public function findAll(array $filters = []): array;
    public function findById(string $id): ?Shop;
    public function insert(Shop $shop): void;
    public function update(Shop $shop): void;
    public function delete(string $id): void;
}