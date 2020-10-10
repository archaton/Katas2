<?php

declare(strict_types=1);

namespace Katas\BookingHotel\Service;

use Katas\BookingHotel\Domain\Booking;
use Katas\BookingHotel\Persistance\WriteRegistry;
use RuntimeException;

class RoomCommandService
{
    private WriteRegistry $writeRegistry;
    private RoomsQueryService $queryService;

    public function __construct(WriteRegistry $writeRegistry, RoomsQueryService $queryService)
    {
        $this->writeRegistry = $writeRegistry;
        $this->queryService = $queryService;
    }

    public function bookARoom(Booking $booking): void
    {
        if (!$this->isRoomFree($booking)) {
            throw new RuntimeException('Room is not free');
        }
        $this->writeRegistry->add($booking);
    }

    private function isRoomFree(Booking $booking): bool
    {
        $freeRooms = $this->queryService->freeRooms($booking->getArrivalDate(), $booking->getDepartureDate());

        foreach ($freeRooms as $freeRoom) {
            if ($freeRoom->is($booking->getRoomName())) {
                return true;
            }
        }

        return false;
    }
}
