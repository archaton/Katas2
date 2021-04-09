<?php

declare(strict_types=1);


namespace Katas\UnusualSpending;


use DateTimeImmutable;

interface Clock
{
//    public function now(): DateTimeImmutable;
    public function currentMonth(): BillingMonth;
    public function previousMonth(): BillingMonth;
}
