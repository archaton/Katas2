<?php

declare(strict_types=1);

namespace Katas\UnusualSpending;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class BillingMonthCreationTest extends TestCase
{
    /**
     * @dataProvider provideValidBillingMonthCases
     */
    public function testBillingMonths(
        string $givenDate,
        int $expectedMonth,
        int $expectedYear,
        int $expectedPreviousMonth,
        int $expectedPreviousYear,
    )
    {
        $now = DateTimeImmutable::createFromFormat('Y-m-d', $givenDate);
        $clock = new FrozenClock($now);

        $current = $clock->currentMonth();
        $previous = $clock->previousMonth();

        $this->assertEquals($expectedMonth, $current->getMonth());
        $this->assertEquals($expectedYear, $current->getYear());
        $this->assertEquals($expectedPreviousMonth, $previous->getMonth());
        $this->assertEquals($expectedPreviousYear, $previous->getYear());
    }

    public function provideValidBillingMonthCases()
    {
        return [
            'normal case' => ['2021-04-10', 4, 2021, 3, 2021],
            'year switch' => ['2021-01-10', 1, 2021, 12, 2020],
        ];
    }
}
