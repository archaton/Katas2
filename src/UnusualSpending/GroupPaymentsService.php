<?php

declare(strict_types=1);


namespace Katas\UnusualSpending;


use Spending\PaymentApi\Payment;

class GroupPaymentsService
{
    /**
     * @param array<Payment> $payments
     * @return array<GroupedPayments>
     */
    public function groupByCategory(array $payments): array
    {
        $groupedPayments = [];
        foreach ($payments as $payment) {
            $group = $groupedPayments[$payment->getCategory()->getValue()] ?? new GroupedPayments(new Category($payment->getCategory()), []);
            $group->add($payment);
            $groupedPayments[$payment->getCategory()->getValue()] = $group;
        }

        return array_values($groupedPayments);
    }
}
