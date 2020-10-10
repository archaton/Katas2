<?php

declare(strict_types=1);

namespace Katas\BookingHotel\Domain;


use DateTimeImmutable;
use Katas\BookingHotel\Persistance\Projection;

class Booking implements DomainEvent, Projection// TODO remove projection
{
    private int$clientId;
    private string $roomName;
    private DateTimeImmutable $arrivalDate;
    private DateTimeImmutable $departureDate;

    public function __construct(
        int $clientId,
        string $roomName,
        DateTimeImmutable $arrivalDate,
        DateTimeImmutable $departureDate
    ) {
        $this->clientId = $clientId;
        $this->roomName = $roomName;
        $this->arrivalDate = $arrivalDate;
        $this->departureDate = $departureDate;
    }

    //TODO remove getters, they're only for the projection part
    public function getClientId(): int
    {
        return $this->clientId;
    }

    public function getRoomName(): string
    {
        return $this->roomName;
    }

    public function getArrivalDate(): DateTimeImmutable
    {
        return $this->arrivalDate;
    }

    public function getDepartureDate(): DateTimeImmutable
    {
        return $this->departureDate;
    }
}
