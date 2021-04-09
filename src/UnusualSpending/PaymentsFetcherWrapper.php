<?php

declare(strict_types=1);


namespace Katas\UnusualSpending;


use Spending\PaymentApi\FetchesUserPaymentsByMonth;
use Spending\PaymentApi\Payment;

class PaymentsFetcherWrapper
{
    private FetchesUserPaymentsByMonth $fetcher;

    public function __construct()
    {
        $this->fetcher = FetchesUserPaymentsByMonth::getInstance();
    }

    /**
     * @return array<Payment>
     */
    public function fetch(int $userId, BillingMonth $month): array
    {
        return $this->fetcher->fetch($userId, $month->getYear(), $month->getMonth());
    }
}
