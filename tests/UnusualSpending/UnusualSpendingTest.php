<?php

declare(strict_types=1);

namespace Katas\UnusualSpending;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Spending\PaymentApi\Category as ApiCategory;
use Spending\PaymentApi\Payment as ApiPayment;

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
            new ApiPayment(50.0, 'shop', new ApiCategory(ApiCategory::GROCERIES)),
            new ApiPayment(48.0, 'shop', new ApiCategory(ApiCategory::GROCERIES)),
            new ApiPayment(300.0, 'trip', new ApiCategory(ApiCategory::TRAVEL)),
            new ApiPayment(328.0, 'trip', new ApiCategory(ApiCategory::TRAVEL)),
            new ApiPayment(25.0, 'tournament', new ApiCategory(ApiCategory::GOLF)),
            new ApiPayment(10.0, 'movie', new ApiCategory(ApiCategory::ENTERTAINMENT)),
        ];
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
        $payments = [
            ...$groceries,
            ...$travels,
            new ApiPayment(50.0, 'tournament', new ApiCategory(ApiCategory::GOLF)),
            new ApiPayment(14.99, 'movie', new ApiCategory(ApiCategory::ENTERTAINMENT)),
        ];
        $filteredCategories = [
            new GroupedPayments(Category::fromString(ApiCategory::GROCERIES), $groceries),
            new GroupedPayments(Category::fromString(ApiCategory::TRAVEL), $travels),
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

    public function testFinal()
    {
        $userId = 1;

        $clock = new FrozenClock(DateTimeImmutable::createFromFormat('Y-m-d', '2021-04-14'));
        $fetcher = $this->createMock(PaymentsFetcherWrapper::class);
        $groupPaymentsService = new GroupPaymentsService();
        $filterPaymentsService = new FilterPaymentsService();
        $service = new PaymentsService(
            $groupPaymentsService,
            $filterPaymentsService,
        );
        $emailComposer = new EmailComposerService();
        $sender = $this->createMock(NotificationServiceWrapper::class);

        $currentMonth = new BillingMonth(4, 2021);
        $previousMonth = new BillingMonth(3, 2021);

        $previousPayments = [
            new ApiPayment(50.0, 'shop', new ApiCategory(ApiCategory::GROCERIES)),
            new ApiPayment(48.0, 'shop', new ApiCategory(ApiCategory::GROCERIES)),
            new ApiPayment(200.0, 'trip', new ApiCategory(ApiCategory::TRAVEL)),
            new ApiPayment(215.0, 'trip', new ApiCategory(ApiCategory::TRAVEL)),
            new ApiPayment(36.0, 'tournament', new ApiCategory(ApiCategory::GOLF)),
            new ApiPayment(10.0, 'movie', new ApiCategory(ApiCategory::ENTERTAINMENT)),
        ];
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

        $fetcher
            ->expects($this->exactly(2))
            ->method('fetch')
            ->withConsecutive([$userId, $currentMonth], [$userId, $previousMonth])
            ->willReturnOnConsecutiveCalls($payments, $previousPayments);

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
