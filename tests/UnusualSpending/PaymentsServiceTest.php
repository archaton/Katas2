<?php

declare(strict_types=1);

namespace Katas\UnusualSpending;

use PHPUnit\Framework\TestCase;
use Spending\PaymentApi\Category as ApiCategory;
use Spending\PaymentApi\Payment as ApiPayment;

class PaymentsServiceTest extends TestCase
{

    public function testGetUnusualSpendingCategories()
    {
        $threshold = 0.5;

        $groupPaymentsService = $this->createMock(GroupPaymentsService::class);
        $filterPaymentsService = $this->createMock(FilterPaymentsService::class);

        $payments = [
            new ApiPayment(50.0, 'shop', new ApiCategory(ApiCategory::GROCERIES)),
            new ApiPayment(50.0, 'shop', new ApiCategory(ApiCategory::GROCERIES)),
            new ApiPayment(48.0, 'shop', new ApiCategory(ApiCategory::GROCERIES)),
            new ApiPayment(300.0, 'trip', new ApiCategory(ApiCategory::TRAVEL)),
            new ApiPayment(300.0, 'trip', new ApiCategory(ApiCategory::TRAVEL)),
            new ApiPayment(328.0, 'trip', new ApiCategory(ApiCategory::TRAVEL)),
            new ApiPayment(50.0, 'tournament', new ApiCategory(ApiCategory::GOLF)),
            new ApiPayment(14.99, 'movie', new ApiCategory(ApiCategory::ENTERTAINMENT)),
        ];
        $previousPayments = [
            new ApiPayment(50.0, 'shop', new ApiCategory(ApiCategory::GROCERIES)),
            new ApiPayment(48.0, 'shop', new ApiCategory(ApiCategory::GROCERIES)),
            new ApiPayment(300.0, 'trip', new ApiCategory(ApiCategory::TRAVEL)),
            new ApiPayment(328.0, 'trip', new ApiCategory(ApiCategory::TRAVEL)),
//            new Payment(25.0, 'tournament', new Category(Category::GOLF)),
            new ApiPayment(10.0, 'movie', new ApiCategory(ApiCategory::ENTERTAINMENT)),
        ];
//        $expectedFilteredCategories = [
//            new SpendingCategory(Category::fromString(ApiCategory::GROCERIES), 148.0),
//            new SpendingCategory(Category::fromString(ApiCategory::TRAVEL), 928.0),
//        ];

        $groupedPayments = [
            $this->createMock(GroupedPayments::class),//groceries
            $this->createMock(GroupedPayments::class),//travel
            $this->createMock(GroupedPayments::class),//golf
            $this->createMock(GroupedPayments::class),//entertainment
        ];
        $groupedPreviousPayments = [
            $this->createMock(GroupedPayments::class),//groceries
            $this->createMock(GroupedPayments::class),//travel
//            $this->createMock(GroupedPayments::class),//golf
            $this->createMock(GroupedPayments::class),//entertainment
        ];
        $expectedFilteredPayments = [
            $this->createMock(GroupedPayments::class),//groceries
            $this->createMock(GroupedPayments::class),//travel
        ];

        $groupPaymentsService
            ->expects($this->exactly(2))
            ->method('groupByCategory')
            ->withConsecutive([$payments], [$previousPayments])
            ->willReturnOnConsecutiveCalls($groupedPayments, $groupedPreviousPayments);

        $filterPaymentsService
            ->expects($this->once())
            ->method('filterUnusualSpending')
            ->with($threshold, $groupedPayments, $groupedPreviousPayments)
            ->willReturn($expectedFilteredPayments);

        $paymentsService = new PaymentsService(
            $groupPaymentsService,
            $filterPaymentsService,
        );

        $filteredPayments = $paymentsService->getUnusualSpendingCategories(
            $payments,
            $previousPayments,
            threshold: $threshold
        );

        $this->assertEquals($expectedFilteredPayments, $filteredPayments);
    }
}
