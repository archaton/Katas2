<?php

declare(strict_types=1);

namespace Katas\BookingHotel\Persistance;


interface Observable
{
    public function attach(Observer $observer): void;

    public function detach(Observer $observer): void;
}
