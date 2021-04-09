<?php

declare(strict_types=1);


namespace Katas\UnusualSpending;


class UnusualSpendingEmailMessage
{
    public function __construct(private string $subject, private string $body)
    {
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getBody(): string
    {
        return $this->body;
    }
}
