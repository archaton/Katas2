<?php

declare(strict_types=1);

namespace Katas\UnusualSpending;

use PHPUnit\Framework\TestCase;
use Spending\PaymentApi\Category;
use Spending\PaymentApi\Payment;

class UnusualSpendingTest extends TestCase
{
    public function testCollaboration()
    {
        $userId = 1;
        $threshold = 0.5;

        $clock = $this->createMock(Clock::class);
        $fetcher = $this->createMock(PaymentsFetcherWrapper::class);
        $service = $this->createMock(PaymentsService::class);
        $emailComposer = $this->createMock(EmailComposerService::class);
        $sender = $this->createMock(NotificationServiceWrapper::class);

        $currentMonth = new BillingMonth(4, 2021);
        $previousMonth = new BillingMonth(3, 2021);

        $previousPayments = [
            new Payment(50.0, 'shop', new Category(Category::GROCERIES)),
            new Payment(48.0, 'shop', new Category(Category::GROCERIES)),
            new Payment(300.0, 'trip', new Category(Category::TRAVEL)),
            new Payment(328.0, 'trip', new Category(Category::TRAVEL)),
            new Payment(25.0, 'tournament', new Category(Category::GOLF)),
            new Payment(10.0, 'movie', new Category(Category::ENTERTAINMENT)),
        ];
        $payments = [
            new Payment(50.0, 'shop', new Category(Category::GROCERIES)),
            new Payment(50.0, 'shop', new Category(Category::GROCERIES)),
            new Payment(48.0, 'shop', new Category(Category::GROCERIES)),
            new Payment(300.0, 'trip', new Category(Category::TRAVEL)),
            new Payment(300.0, 'trip', new Category(Category::TRAVEL)),
            new Payment(328.0, 'trip', new Category(Category::TRAVEL)),
            new Payment(50.0, 'tournament', new Category(Category::GOLF)),
            new Payment(14.99, 'movie', new Category(Category::ENTERTAINMENT)),
        ];
        $filteredCategories = [
            new SpendingCategory(new Category(Category::GROCERIES), 148.0),
            new SpendingCategory(new Category(Category::TRAVEL), 928.0),
        ];
        $subject = 'Unusual spending of $1076 detected!';
        $body = <<<TEXT
Hello card user!

We have detected unusually high spending on your card in these categories:

* You spent $148 on groceries
* You spent $928 on travel

Love,

The Credit Card Company
TEXT;

        $emailMessage = new UnusualSpendingEmailMessage($subject, $body);

        $clock
            ->expects($this->once())
            ->method('currentMonth')
            ->willReturn($currentMonth);
        $clock
            ->expects($this->once())
            ->method('previousMonth')
            ->willReturn($previousMonth);

        $fetcher
            ->expects($this->exactly(2))
            ->method('fetch')
            ->withConsecutive([$userId, $currentMonth], [$userId, $previousMonth])
            ->willReturnOnConsecutiveCalls($payments, $previousPayments);

        $service
            ->expects($this->once())
            ->method('getUnusualSpendingCategories')
            ->with($payments, $previousPayments, $threshold)
            ->willReturn($filteredCategories);

        $emailComposer
            ->expects($this->once())
            ->method('composeUnusualSpendingEmail')
            ->with($filteredCategories)
            ->willReturn($emailMessage);

        $sender
            ->expects($this->once())
            ->method('send')
            ->with($userId, $emailMessage);

        $spending = new TriggerUnusualSpendingEmail(
            $clock,
            $fetcher,
            $service,
            $emailComposer,
            $sender,
        );
        $spending->trigger($userId);
    }
}
