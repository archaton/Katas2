<?php

declare(strict_types=1);


namespace Katas\UnusualSpending;


class FilterPaymentsService
{
    public function __construct()
    {
    }

    /**
     * @param array<GroupedPayments> $groupedPayments
     * @param array<GroupedPayments> $previousGroupedPayments
     * @return array<GroupedPayments>
     */
    public function filterUnusualSpending(float $threshold, array $groupedPayments, array $previousGroupedPayments): array
    {
        $percentage = 1. + $threshold;

        $unusualSpendingPayments = [];
        foreach ($groupedPayments as $groupedPayment) {
            $previousMatchingPayment = $this->findMatchingPayment($groupedPayment, $previousGroupedPayments);
            if (null === $previousMatchingPayment) {
                $unusualSpendingPayments[] = $groupedPayment;
                break;
            }
            if ($this->isUnusualSpending($previousMatchingPayment, $percentage, $groupedPayment)) {
                $unusualSpendingPayments[] = $groupedPayment;
            }
        }
        return $unusualSpendingPayments;
    }

    /**
     * @param array<GroupedPayments> $stack
     */
    private function findMatchingPayment(GroupedPayments $needle, array $stack): ?GroupedPayments
    {
        foreach ($stack as $item) {
            if ($needle->getCategory()->equals($item->getCategory())) {
                return $item;
            }
        }
        return null;
    }

    public function isUnusualSpending(GroupedPayments $previousMatchingPayment, float $percentage, GroupedPayments $groupedPayment): bool
    {
        return $previousMatchingPayment->total() * $percentage < $groupedPayment->total();
    }
}
