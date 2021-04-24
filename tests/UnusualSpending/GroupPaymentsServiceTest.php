<?php

declare(strict_types=1);

namespace Katas\UnusualSpending;

use PHPUnit\Framework\TestCase;
use Spending\PaymentApi\Category as ApiCategory;
use Spending\PaymentApi\Payment as ApiPayment;

class GroupPaymentsServiceTest extends TestCase
{

    public function testGroupByCategory()
    {
        $groceries = [
            new ApiPayment(50.0, 'shop', new ApiCategory(ApiCategory::GROCERIES)),
            new ApiPayment(50.0, 'shop', new ApiCategory(ApiCategory::GROCERIES)),
            new ApiPayment(48.0, 'shop', new ApiCategory(ApiCategory::GROCERIES)),
        ];
        $travels = [
            new ApiPayment(300.0, 'trip', new ApiCategory(ApiCategory::TRAVEL)),
            new ApiPayment(300.0, 'trip', new ApiCategory(ApiCategory::TRAVEL)),
            new ApiPayment(328.0, 'trip', new ApiCategory(ApiCategory::TRAVEL)),
        ];
        $golfs = [
            new ApiPayment(50.0, 'tournament', new ApiCategory(ApiCategory::GOLF)),
        ];
        $entertainments = [
            new ApiPayment(14.99, 'movie', new ApiCategory(ApiCategory::ENTERTAINMENT)),
        ];

        /** @var ApiPayment[] $givenPayments */
        $givenPayments = [
            ...$groceries,
            ...$travels,
            ...$golfs,
            ...$entertainments,
        ];
        $expectedGroupedPayments = [
            new GroupedPayments(Category::fromString(ApiCategory::GROCERIES), $groceries),//groceries
            new GroupedPayments(Category::fromString(ApiCategory::TRAVEL), $travels),//travel
            new GroupedPayments(Category::fromString(ApiCategory::GOLF), $golfs),//golf
            new GroupedPayments(Category::fromString(ApiCategory::ENTERTAINMENT), $entertainments),//entertainment
        ];
        $groupPaymentService = new GroupPaymentsService();

        $groupedPayments = $groupPaymentService->groupByCategory($givenPayments);

        $this->assertEquals($expectedGroupedPayments, $groupedPayments);

        $expectedSums = [
            ApiCategory::GROCERIES => 148.0,
            ApiCategory::TRAVEL => 928.0,
            ApiCategory::GOLF => 50.0,
            ApiCategory::ENTERTAINMENT => 14.99,
        ];
        foreach ($groupedPayments as $payment) {
            $this->assertSame($expectedSums[$payment->getCategory()->getValue()], $payment->total());
        }
    }
}
