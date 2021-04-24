<?php

declare(strict_types=1);


namespace Katas\UnusualSpending;


use Spending\PaymentApi\Payment;

class PaymentsService
{
    public function __construct(
        private GroupPaymentsService $groupPaymentsService,
        private FilterPaymentsService $filterPaymentsService,
    )
    {
    }

    /**
     * @param array<Payment> $payments
     * @param array<Payment> $previousPayments
     * @return array<GroupedPayments>
     */
    public function getUnusualSpendingCategories(array $payments, array $previousPayments, float $threshold): array
    {
        $groupedPayments = $this->groupPaymentsService->groupByCategory($payments);
        $previousGroupedPayments = $this->groupPaymentsService->groupByCategory($previousPayments);

        // filter down to the categories for which the user spent at least 50% more this month than last month
        return $this->filterPaymentsService->filterUnusualSpending($threshold, $groupedPayments, $previousGroupedPayments);
    }
}
