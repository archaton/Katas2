<?php

declare(strict_types=1);


namespace Spending\Email;


use RuntimeException;

class EmailUser
{
    public static function email(int $userId, string $subject, string $body): void
    {
        throw new RuntimeException("Email will be implemented by a different team later");
    }
}
