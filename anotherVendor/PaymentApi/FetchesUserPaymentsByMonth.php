<?php

declare(strict_types=1);


namespace Spending\PaymentApi;


use RuntimeException;

class FetchesUserPaymentsByMonth
{
    private function __construct()
    {
    }

    public static function getInstance(): self
    {
        return new self();
    }

    /**
     * @return array<Payment>
     */
    public function fetch(int $userId, int $year, int $month): array
    {
        throw new RuntimeException("Data access will be implemented by a different team later");
    }
}
