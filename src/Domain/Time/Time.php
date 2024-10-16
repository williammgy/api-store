<?php declare(strict_types=1);

namespace App\Domain\Time;

use JsonSerializable;

class Time implements JsonSerializable
{
    public function __construct(
        private int $hours,
        private int $minutes = 0,
        private int $seconds = 0
    ) {
        if ($hours < 0 || $hours > 23) throw new \InvalidArgumentException('Hours must be between 0 and 23');
        if ($minutes < 0 || $minutes > 59) throw new \InvalidArgumentException('Minutes must be between 0 and 59');
        if ($seconds < 0 || $seconds > 59) throw new \InvalidArgumentException('Seconds must be between 0 and 59');
    }

    public function jsonSerialize(): array
    {
        return [
            'time' => $this->__toString()
        ];
    }

    public function __toString(): string
    {
        return sprintf('%02d:%02d:%02d', $this->hours, $this->minutes, $this->seconds);
    }

    public function getHours(): int
    {
        return $this->hours;
    }

    public function getMinutes(): int
    {
        return $this->minutes;
    }

    public function getSeconds(): int
    {
        return $this->seconds;
    }
}