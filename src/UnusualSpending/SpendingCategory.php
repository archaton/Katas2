<?php

declare(strict_types=1);


namespace Katas\UnusualSpending;


class SpendingCategory
{
    public function __construct(
        private Category $category,
        private float $amount,
    )
    {
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }
}
