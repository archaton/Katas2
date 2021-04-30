<?php

declare(strict_types=1);

namespace Katas\UnusualSpending;

use PHPUnit\Framework\TestCase;
use Spending\PaymentApi\Category as ApiCategory;
use Spending\PaymentApi\Payment as ApiPayment;

class EmailComposerServiceTest extends TestCase
{
    /**
     * @dataProvider provideMessageCases
     * @param array<GroupedPayments> $groupedPayments
     */
    public function testComposeUnusualSpendingEmail(string $expectedSubject, string $expectedBody, array $groupedPayments)
    {
        $emailComposer = new EmailComposerService();

        $message = $emailComposer->composeUnusualSpendingEmail($groupedPayments);

        $this->assertSame($expectedSubject, $message->getSubject());
        $this->assertSame($expectedBody, $message->getBody());
    }

    public function provideMessageCases(): array
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
        return [
            'two unusual payments' => [
                'Unusual spending of $1076 detected!',
                <<<TEXT
Hello card user!

We have detected unusually high spending on your card in these categories:

* You spent $148 on groceries
* You spent $928 on travel

Love,

The Credit Card Company
TEXT,
                [
                    new GroupedPayments(Category::fromString(ApiCategory::GROCERIES), $groceries),
                    new GroupedPayments(Category::fromString(ApiCategory::TRAVEL), $travels),
                ]
            ],
        ];
    }
}
