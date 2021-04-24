<?php

declare(strict_types=1);


namespace Katas\UnusualSpending;


use Spending\PaymentApi\Payment;

class GroupedPayments
{
    /**
     * @param array<Payment> $payments
     */
    public function __construct(private Category $category, private array $payments)
    {
        foreach ($payments as $payment) {
            if (!$category->is($payment->getCategory()->getValue())) {
                throw new InvalidArgumentException();
            }
        }
    }

    public function add(Payment $payment): void
    {
        if (!$this->category->is($payment->getCategory()->getValue())) {
            throw new InvalidArgumentException();
        }
        $this->payments[] = $payment;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function total(): float
    {
        return array_reduce($this->payments, static fn (float $acc, Payment $item): float => $acc + $item->getPrice(), 0.0);
    }
}
