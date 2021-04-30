<?php

declare(strict_types=1);


namespace Katas\UnusualSpending;


class EmailComposerService
{
    private const SUBJECT_TEMPLATE = 'Unusual spending of $%s detected!';
    private const BODY_TEMPLATE = <<<TXT
Hello card user!

We have detected unusually high spending on your card in these categories:

%s

Love,

The Credit Card Company
TXT;
    private const SPENT_TEMPLATE = '* You spent $%s on %s';


    /**
     * @param array<GroupedPayments> $groupedPayments
     */
    public function composeUnusualSpendingEmail(array $groupedPayments): UnusualSpendingEmailMessage
    {
        $subject = sprintf(self::SUBJECT_TEMPLATE, $this->getTotal($groupedPayments));
        $payments = array_map(
            static fn (GroupedPayments $groupedPayment) => sprintf(self::SPENT_TEMPLATE, $groupedPayment->total(), $groupedPayment->getCategory()->getValue()),
            $groupedPayments
        );
        $body = sprintf(self::BODY_TEMPLATE, implode("\n", $payments));

        return new UnusualSpendingEmailMessage($subject, $body);
    }

    private function getTotal(array $groupedPayments): float
    {
        return array_reduce($groupedPayments, static fn (float $acc, GroupedPayments $item) => $acc + $item->total(), 0.0);
    }
}
