<?php declare(strict_types= 1);

namespace App\Application\Type;

use App\Domain\Type\TypeRepositoryInterface;

class ListTypesHandler
{
    public function __construct(
        private TypeRepositoryInterface $typeRepository
    ) {}

    public function handle(): array
    {
        $this->typeRepository->findAll();
    }
}