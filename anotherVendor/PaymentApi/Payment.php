<?php

declare(strict_types=1);


namespace Spending\PaymentApi;


class Payment
{
    private float $price;
    private string $description;
    private Category $category;

    public function __construct(float $price, string $description, Category $category)
    {
        $this->price = $price;
        $this->description = $description;
        $this->category = $category;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }
}
