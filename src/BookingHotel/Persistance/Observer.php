<?php

declare(strict_types=1);

namespace Katas\BookingHotel\Persistance;


interface Observer
{
    public function update(Projection $subject): void;

    public function supports(Projection $project): bool;
}
