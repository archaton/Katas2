<?php

declare(strict_types=1);

namespace Katas;

use Katas\BookingHotel\Domain\Booking;
use Katas\BookingHotel\Domain\Room;
use Katas\BookingHotel\Persistance\EventProjectionMapper;
use Katas\BookingHotel\Persistance\ReadRegistry;
use Katas\BookingHotel\Persistance\WriteRegistry;
use Katas\BookingHotel\Service\RoomCommandService;
use PHPUnit\Framework\TestCase;
use Katas\BookingHotel\Service\RoomsQueryService;

class BookingTest extends TestCase
{
    private RoomsQueryService $queryService;
    private RoomCommandService $commandService;
    private ReadRegistry $readRegistry;
    private WriteRegistry $writeRegistry;

    protected function setUp(): void
    {
        $this->readRegistry = new ReadRegistry();
        $this->writeRegistry = new WriteRegistry(new EventProjectionMapper());
        $this->writeRegistry->attach($this->readRegistry);
        $this->queryService = new RoomsQueryService($this->readRegistry);
        $this->commandService = new RoomCommandService($this->writeRegistry, $this->queryService);
    }

    public function testNoRoomsAvailable()
    {
        $this->readRegistry->initialize([
            Room::class => [],
        ]);
        $arrivalDate = new \DateTimeImmutable();
        $departureDate = $arrivalDate->modify('+2 days');

        $rooms = $this->queryService->freeRooms($arrivalDate, $departureDate);

        $this->assertSame([], $rooms);
    }

    public function testSeeAllRooms()
    {
        $this->readRegistry->initialize([
            Room::class => [
                'room 1',
                'room 2',
                'room 3',
                'room 4',
                'room 5',
            ],
        ]);
        $arrivalDate = new \DateTimeImmutable();
        $departureDate = $arrivalDate->modify('+2 days');

        $rooms = $this->queryService->freeRooms($arrivalDate, $departureDate);

        $this->assertCount(5, $rooms);
        foreach ($rooms as $room) {
            $this->assertInstanceOf(Room::class, $room);
        }
    }

    public function testAllButOneRoomFree()
    {
        $this->readRegistry->initialize([
            Room::class => [
                'room 1',
                'room 2',
                'room 3',
                'room 4',
                'room 5',
            ],
        ]);
        $arrivalDate = new \DateTimeImmutable();
        $departureDate = $arrivalDate->modify('+2 days');

        $this->commandService->bookARoom(new Booking(
            1,
            'room 1',
            $arrivalDate->modify('+1 days'),
            $arrivalDate->modify('+2 days')
        ));

        $rooms = $this->queryService->freeRooms($arrivalDate, $departureDate);

        $this->assertCount(4, $rooms);
    }

    public function testAllButOneRoomTaken()
    {
        $this->readRegistry->initialize([
            Room::class => [
                'room 1',
                'room 2',
                'room 3',
                'room 4',
                'room 5',
            ],
        ]);
        $arrivalDate = new \DateTimeImmutable();
        $departureDate = $arrivalDate->modify('+2 days');

        $this->commandService->bookARoom(new Booking(
            1,
            'room 1',
            $arrivalDate->modify('+1 days'),
            $arrivalDate->modify('+2 days')
        ));
        $this->commandService->bookARoom(new Booking(
            1,
            'room 2',
            $arrivalDate->modify('+1 days'),
            $arrivalDate->modify('+2 days')
        ));
        $this->commandService->bookARoom(new Booking(
            1,
            'room 3',
            $arrivalDate->modify('+1 days'),
            $arrivalDate->modify('+2 days')
        ));
        $this->commandService->bookARoom(new Booking(
            1,
            'room 4',
            $arrivalDate->modify('+1 days'),
            $arrivalDate->modify('+2 days')
        ));

        $rooms = $this->queryService->freeRooms($arrivalDate, $departureDate);

        $this->assertCount(1, $rooms);
        $this->assertSame('room 5', $rooms[0]->__toString());
    }

    public function testOneJustLeaving()
    {
        $this->readRegistry->initialize([
            Room::class => [
                'room 1',
            ],
        ]);
        $arrivalDate = new \DateTimeImmutable();
        $departureDate = $arrivalDate->modify('+2 days');

        $this->commandService->bookARoom(new Booking(
            1,
            'room 1',
            $arrivalDate->modify('-1 days'),
            $arrivalDate
        ));

        $rooms = $this->queryService->freeRooms($arrivalDate, $departureDate);

        $this->assertCount(1, $rooms);
    }

    public function tesOneJustComing()
    {
        $this->readRegistry->initialize([
            Room::class => [
                'room 1',
            ],
        ]);
        $arrivalDate = new \DateTimeImmutable();
        $departureDate = $arrivalDate->modify('+2 days');

        $this->commandService->bookARoom(new Booking(
            1,
            'room 1',
            $departureDate,
            $departureDate->modify('+2 days')
        ));

        $rooms = $this->queryService->freeRooms($arrivalDate, $departureDate);

        $this->assertCount(1, $rooms);
    }
}
