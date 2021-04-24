<?php

declare(strict_types=1);

namespace Katas\UnusualSpending;

use PHPUnit\Framework\TestCase;
use Spending\PaymentApi\Category as ApiCategory;
use Spending\PaymentApi\Payment as ApiPayment;

class GroupedPaymentsTest extends TestCase
{
    public function testFailGroup()
    {
        $payments = [
            new ApiPayment(50.0, 'shop', new ApiCategory(ApiCategory::GROCERIES)),
            new ApiPayment(50.0, 'shop', new ApiCategory(ApiCategory::GROCERIES)),
            new ApiPayment(300.0, 'trip', new ApiCategory(ApiCategory::TRAVEL)),
        ];
        $this->expectException(InvalidArgumentException::class);
        $groupedPayments = new GroupedPayments(Category::fromString(ApiCategory::GROCERIES), $payments);
    }

    public function testSuccessGroup()
    {
        $payments = [
            new ApiPayment(50.0, 'shop', new ApiCategory(ApiCategory::GROCERIES)),
            new ApiPayment(50.0, 'shop', new ApiCategory(ApiCategory::GROCERIES)),
            new ApiPayment(48.0, 'shop', new ApiCategory(ApiCategory::GROCERIES)),
        ];
        $expectedSum = 148.0;
        $category = Category::fromString(ApiCategory::GROCERIES);
        $groupedPayments = new GroupedPayments($category, $payments);

        $this->assertSame($category, $groupedPayments->getCategory());
        $this->assertSame($expectedSum, $groupedPayments->total());
    }
}
