<?php

declare(strict_types=1);


namespace Katas\UnusualSpending;


use Spending\PaymentApi\Payment;

class PaymentsService
{

    /**
     * @param array<Payment> $payments
     * @return array
     */
    public function groupByCategory(array $payments): array
    {

    }

    /**
     * @param array<Payment> $payments
     * @param array<Payment> $previousPayments
     * @param float $threshold
     * @return array
     */
    public function getUnusualSpendingCategories(array $payments, array $previousPayments, float $threshold): array
    {
        $groupedPayments = $this->groupByCategory($payments);
//        $previousGroupedPayments = $this->groupByCategory($previousPayments);

        // filter down to the categories for which the user spent at least 50% more this month than last month
//        $mergedCategories = $this->mergeMonths($groupedPayments, $previousGroupedPayments);
//        $filteredCategories = $this->filterUnusualSpending(threshold: 0.5);// TODO get threshold from User?
    }
}
