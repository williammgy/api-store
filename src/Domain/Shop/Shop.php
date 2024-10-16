<?php declare(strict_types=1);

namespace App\Domain\Shop;

use App\Domain\Type\Type;
use App\Domain\Time\Time;

use JsonSerializable;
use Ramsey\Uuid\Uuid;

class Shop implements JsonSerializable
{
    public function __construct(
        private string $name,
        private Type $type,
        private string $address,
        private Time $openingHour,
        private Time $closingHour,
        private ?string $slogan = null,
        private ?string $id = null
    ) {
        $this->id = $id ?? Uuid::uuid4()->toString();
    }

    public function jsonSerialize(): array
    {
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'slogan'       => $this->slogan,
            'type'         => $this->type,
            'address'      => $this->address,
            'opening_hour' => $this->openingHour,
            'closing_hour' => $this->closingHour
        ];
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSlogan(): ?string
    {
        return $this->slogan;
    }

    public function getType(): Type
    {
        return $this->type;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getOpeningHour(): Time
    {
        return $this->openingHour;
    }

    public function getClosingHour(): Time
    {
        return $this->closingHour;
    }

    public function update(
        string $name,
        Type $type,
        string $address,
        Time $openingHour,
        Time $closingHour,
        ?string $slogan = null
    ) : void
    {
        $this->name = $name;
        $this->slogan = $slogan;
        $this->type = $type;
        $this->address = $address;
        $this->openingHour = $openingHour;
        $this->closingHour = $closingHour;
    }
}