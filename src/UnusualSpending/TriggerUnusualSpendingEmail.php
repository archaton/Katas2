<?php

declare(strict_types=1);


namespace Katas\UnusualSpending;


class TriggerUnusualSpendingEmail
{
    public function __construct(
        private Clock $clock,
        private PaymentsFetcherWrapper $paymentsFetcher,
        private PaymentsService $paymentsService,
        private EmailComposerService $emailComposer,
        private NotificationServiceWrapper $notificationService,
    ) {
    }

    public function trigger(int $userId): void
    {
        // get the payments of current month
        $month = $this->clock->currentMonth();
        $payments = $this->paymentsFetcher->fetch($userId, $month);

        // get the payments of the previous month
        $previousMonth = $this->clock->previousMonth();
        $previousPayments = $this->paymentsFetcher->fetch($userId, $previousMonth);

        // Compare the total amount paid for the each month, grouped by category
        $filteredCategories = $this->paymentsService->getUnusualSpendingCategories(
            $payments,
            $previousPayments,
            threshold: 0.5
        );

        // Compose an e-mail message to the user that lists the categories for which spending was unusually high
        $emailMessage = $this->emailComposer->composeUnusualSpendingEmail($filteredCategories);
        $this->notificationService->send($userId, $emailMessage);
    }
}
