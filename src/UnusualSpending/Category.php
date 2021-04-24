<?php

declare(strict_types=1);


namespace Katas\UnusualSpending;


use Spending\PaymentApi\Category as ApiCategory;

class Category
{
    public function __construct(private ApiCategory $category)
    {
    }

    public static function fromString(string $category): self
    {
        return new self(new ApiCategory($category));
    }

    public function getValue(): string
    {
        return $this->category->getValue();
    }

    public function equals(self $category): bool
    {
        return $this->category->getValue() === $category->category->getValue();
    }

    public function is(string $category): bool
    {
        return $this->category->getValue() === $category;
    }
}
