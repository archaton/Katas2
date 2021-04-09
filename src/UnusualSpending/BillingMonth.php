<?php

declare(strict_types=1);


namespace Katas\UnusualSpending;


class BillingMonth
{
    public function __construct(
        private int $month,
        private int $year,
    )
    {
    }

    public static function fromDateTime(\DateTimeImmutable $now): self
    {
        return new self((int) $now->format('m'), (int) $now->format('Y'));
    }

    public function getMonth(): int
    {
        return $this->month;
    }

    public function getYear(): int
    {
        return $this->year;
    }
}
