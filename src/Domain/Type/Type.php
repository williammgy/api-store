<?php declare(strict_types=1);

namespace App\Domain\Type;

use JsonSerializable;

class Type implements JsonSerializable
{
    public function __construct(
        private string $label,
        private ?int $id = null
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'id'    => $this->id,
            'label' => $this->label
        ];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getLabel(): string
    {
        return $this->label;
    }
}