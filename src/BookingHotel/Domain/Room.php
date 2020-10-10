<?php

declare(strict_types=1);

namespace Katas\BookingHotel\Domain;


class Room
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function is(string $name): bool
    {
        return $this->name === $name;
    }

    public function equals(self $room): bool
    {
        return $this->name === $room->name;
    }
}
