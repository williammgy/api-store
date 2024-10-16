<?php declare(strict_types=1);

namespace App\Domain\Type;

interface TypeRepositoryInterface
{
    public function findAll(): array;
    public function findById(int $id): ?Type;
}