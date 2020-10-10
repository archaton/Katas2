<?php

declare(strict_types=1);

namespace Katas\BookingHotel\Persistance;


use Katas\BookingHotel\Domain\Booking;
use Katas\BookingHotel\Domain\Room;

class ReadRegistry implements Observer
{
    /** @var array|string[classname] */
    private array $projectionConsumersMap;
    /** @var array|array[classname] */
    private array $items;

    public function __construct()
    {
        $this->items = [];
        $this->projectionConsumersMap = [
            Booking::class => 'projectBooking',
        ];
    }

    public function initialize(array $fixtures)
    {
        $this->items = [];
        foreach ($fixtures as $className => $roomsNames) {//TODO make more general
            $this->items[$className] = [];
            foreach ($roomsNames as $roomName) {
                $this->items[$className][$roomName] = [];
            }
        }
    }

    public function update(Projection $subject): void
    {
        ([$this, $this->projectionConsumersMap[get_class($subject)]])($subject);
    }

    public function supports(Projection $project): bool
    {
        return array_key_exists(get_class($project), $this->projectionConsumersMap);
    }

    /**
     * @param string $className
     * @return array|array[string] a map of room names to arrival/departure dates
     */
    public function getAll(string $className): array
    {
        if (!array_key_exists($className, $this->items)) {
            throw new \RuntimeException('Not registered class '.$className);
        }

        return $this->items[$className];
    }

    private function projectBooking(Booking $booking): void
    {
        $rooms = $this->items[Room::class];

        $bookedRoom = $rooms[$booking->getRoomName()];//TODO rooms should be a collection object
        $bookedRoom[] = [$booking->getArrivalDate(), $booking->getDepartureDate()];

        $rooms[$booking->getRoomName()] = $bookedRoom;
        $this->items[Room::class] = $rooms;
    }
}
