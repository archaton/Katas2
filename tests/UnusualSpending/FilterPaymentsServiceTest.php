<?php

declare(strict_types=1);

namespace Katas\UnusualSpending;

use PHPUnit\Framework\TestCase;
use Spending\PaymentApi\Category as ApiCategory;
use Spending\PaymentApi\Payment as ApiPayment;

class FilterPaymentsServiceTest extends TestCase
{
    public function testFilteringCondition()
    {
        $threshold1 = 1.5;

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
        $entertainments = [
            new ApiPayment(50.0, 'movie', new ApiCategory(ApiCategory::ENTERTAINMENT)),
        ];
        $groupedPayments = [
            new GroupedPayments(Category::fromString(ApiCategory::GROCERIES), $groceries),//groceries
            new GroupedPayments(Category::fromString(ApiCategory::TRAVEL), $travels),//travel
            new GroupedPayments(Category::fromString(ApiCategory::ENTERTAINMENT), $entertainments),//entertainment
        ];

        $groceriesPrevious = [
            new ApiPayment(50.0, 'shop', new ApiCategory(ApiCategory::GROCERIES)),
            new ApiPayment(48.0, 'shop', new ApiCategory(ApiCategory::GROCERIES)),
        ];
        $travelsPrevious = [
            new ApiPayment(300.0, 'trip', new ApiCategory(ApiCategory::TRAVEL)),
            new ApiPayment(328.0, 'trip', new ApiCategory(ApiCategory::TRAVEL)),
        ];
        $entertainmentsPrevious = [
            new ApiPayment(10.0, 'movie', new ApiCategory(ApiCategory::ENTERTAINMENT)),
        ];
        $groupedPreviousPayments = [
            new GroupedPayments(Category::fromString(ApiCategory::GROCERIES), $groceriesPrevious),//groceries
            new GroupedPayments(Category::fromString(ApiCategory::TRAVEL), $travelsPrevious),//travel
            new GroupedPayments(Category::fromString(ApiCategory::ENTERTAINMENT), $entertainmentsPrevious),//entertainment
        ];

        $filteringService = new FilterPaymentsService();

        $this->assertTrue($filteringService->isUnusualSpending(
            $groupedPreviousPayments[0],
            $threshold1,
            $groupedPayments[0],
        ));
        $this->assertFalse($filteringService->isUnusualSpending(
            $groupedPreviousPayments[1],
            $threshold1,
            $groupedPayments[1],
        ));
        $this->assertTrue($filteringService->isUnusualSpending(
            $groupedPreviousPayments[2],
            $threshold1,
            $groupedPayments[2],
        ));
    }

    public function testFilterUnusualSpending()
    {
        $threshold0 = 0.5;

// current month
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
//        $entertainments = [
//            new ApiPayment(14.99, 'movie', new ApiCategory(ApiCategory::ENTERTAINMENT)),
//        ];
        $groupedPayments = [
            new GroupedPayments(Category::fromString(ApiCategory::GROCERIES), $groceries),//groceries
            new GroupedPayments(Category::fromString(ApiCategory::TRAVEL), $travels),//travel
            new GroupedPayments(Category::fromString(ApiCategory::GOLF), $golfs),//golf
//            new GroupedPayments(Category::fromString(ApiCategory::ENTERTAINMENT), $entertainments),//entertainment
        ];
        //previous month
        $groceriesPrevious = [
            new ApiPayment(50.0, 'shop', new ApiCategory(ApiCategory::GROCERIES)),
            new ApiPayment(48.0, 'shop', new ApiCategory(ApiCategory::GROCERIES)),
        ];
        $travelsPrevious = [
            new ApiPayment(300.0, 'trip', new ApiCategory(ApiCategory::TRAVEL)),
            new ApiPayment(328.0, 'trip', new ApiCategory(ApiCategory::TRAVEL)),
        ];
//        $golfsPrevious = [
//        ];
        $entertainmentsPrevious = [
            new ApiPayment(10.0, 'movie', new ApiCategory(ApiCategory::ENTERTAINMENT)),
        ];
        $groupedPreviousPayments = [
            new GroupedPayments(Category::fromString(ApiCategory::GROCERIES), $groceriesPrevious),//groceries
            new GroupedPayments(Category::fromString(ApiCategory::TRAVEL), $travelsPrevious),//travel
//            $this->createMock(GroupedPayments::class),//golf
            new GroupedPayments(Category::fromString(ApiCategory::ENTERTAINMENT), $entertainmentsPrevious),//entertainment
        ];

        $expectedPayments = [
            new GroupedPayments(Category::fromString(ApiCategory::GROCERIES), $groceries),
//            new GroupedPayments(Category::fromString(ApiCategory::TRAVEL), $travels),
            new GroupedPayments(Category::fromString(ApiCategory::GOLF), $golfs),
        ];

        $filteringService = new FilterPaymentsService();
        $filteredPayments = $filteringService->filterUnusualSpending(
            $threshold0,
            $groupedPayments,
            $groupedPreviousPayments,
        );

        $this->assertEquals($expectedPayments, $filteredPayments);
    }
}
