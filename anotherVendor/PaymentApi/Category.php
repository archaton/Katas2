<?php

declare(strict_types=1);


namespace Spending\PaymentApi;


use InvalidArgumentException;

class Category
{
    public const ENTERTAINMENT = "entertainment";
    public const RESTAURANTS = "restaurants";
    public const GOLF = "golf";
    public const GROCERIES = "groceries";
    public const TRAVEL = "travel";

    public const CATEGORIES = [
        self::ENTERTAINMENT,
        self::RESTAURANTS,
        self::GOLF,
        self::GROCERIES,
        self::TRAVEL,
    ];

    private string $value;

    public function __construct(string $value)
    {
        if (!in_array($value, self::CATEGORIES)) {
            throw new InvalidArgumentException();
        }
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
