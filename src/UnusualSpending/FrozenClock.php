<?php

declare(strict_types=1);


namespace Katas\UnusualSpending;

use DateTimeImmutable;
use DateTimeZone;

final class FrozenClock implements Clock
{
    public function __construct(private DateTimeImmutable $now)
    {
    }

    public static function fromUTC(): self
    {
        return new self(new DateTimeImmutable('now', new DateTimeZone('UTC')));
    }

    public function setTo(DateTimeImmutable $now): void
    {
        $this->now = $now;
    }

    public function now(): DateTimeImmutable
    {
        return $this->now;
    }

    public function currentMonth(): BillingMonth
    {
        return BillingMonth::fromDateTime($this->now());
    }

    public function previousMonth(): BillingMonth
    {
        return BillingMonth::fromDateTime($this->now()->modify('-1 months'));
    }
}
