<?php

declare(strict_types=1);

namespace Katas\BookingHotel\Service;


use DateTimeImmutable;
use Katas\BookingHotel\Domain\Room;
use Katas\BookingHotel\Persistance\ReadRegistry;

class RoomsQueryService
{
    private ReadRegistry $readRegistry;

    public function __construct(ReadRegistry $readRegistry)
    {
        $this->readRegistry = $readRegistry;
    }

    /**
     * @return array|Room[]
     */
    public function freeRooms(DateTimeImmutable $arrivalDate, DateTimeImmutable $departureDate): array
    {
        $freeRooms = [];
        foreach ($this->readRegistry->getAll(Room::class) as $roomName => $items) {
            $reserved = false;
            foreach ($items as $item) {
                [$startDate, $endDate] = $item;
                if ($this->isRoomReserved($arrivalDate, $departureDate, $startDate, $endDate)) {
                    $reserved = true;
                    break;
                }
            }
            if (!$reserved) {
                $freeRooms[] = new Room($roomName);
            }
        }
        return $freeRooms;
    }

    private function isArrivalOverlapping(DateTimeImmutable $arrivalDate, DateTimeImmutable $startDate, DateTimeImmutable $endDate): bool
    {
        return $arrivalDate >= $startDate && $arrivalDate < $endDate;
    }

    private function isDepartureOverlapping(DateTimeImmutable $departureDate, DateTimeImmutable $endDate, DateTimeImmutable $startDate): bool
    {
        return $departureDate <= $endDate && $departureDate > $startDate;
    }

    private function isRoomReserved($arrivalDate, $departureDate, $startDate, $endDate): bool
    {
        return $this->isArrivalOverlapping($arrivalDate, $startDate, $endDate) || $this->isDepartureOverlapping($departureDate, $endDate, $startDate);
    }
}
