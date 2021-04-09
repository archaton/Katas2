<?php

declare(strict_types=1);


namespace Katas\UnusualSpending;


use Spending\Email\EmailUser;

class NotificationServiceWrapper
{
    public function __construct()
    {
    }

    public function send(int $userId, UnusualSpendingEmailMessage $emailMessage): void
    {
        EmailUser::email($userId, $emailMessage->getSubject(), $emailMessage->getBody());
    }
}
