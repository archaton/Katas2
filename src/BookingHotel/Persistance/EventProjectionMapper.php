<?php

declare(strict_types=1);

namespace Katas\BookingHotel\Persistance;


use Katas\BookingHotel\Domain\Booking;
use Katas\BookingHotel\Domain\DomainEvent;
use RuntimeException;

class EventProjectionMapper
{
    /** @var array|callable[classname] */
    private array $mappers;

    public function __construct()
    {
        $this->mappers = [
            Booking::class => function (Booking $booking)/*: BookingProjection*/ {
                return $booking;// TODO make a real mapping
            },
        ];
    }

    public function map(DomainEvent $event): Projection
    {
        $className = get_class($event);
        if (!array_key_exists($className, $this->mappers)) {
            throw new RuntimeException('Missing mapper for domain event '.$className);
        }

        return ($this->mappers[$className])($event);
    }
}
