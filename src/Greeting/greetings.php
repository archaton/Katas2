<?php

declare(strict_types=1);

namespace Katas\Greeting;

const GREET_TEMPLATE = 'Hello, %s.';
const SHOUTING_GREET = 'HELLO %s!';

function greet(?string ...$names): string
{
    if (count($names) === 1) {
        $name = reset($names);

        return handleOneName($name);
    }
    $names = array_reduce($names, static fn (array $acc, string $n):array => handleInput($acc, $n), []);
    $uppercase = array_filter($names, static fn (string $n): bool => isAllUppercase($n));
    $names = array_filter($names, static fn (string $n): bool => !isAllUppercase($n));
    $greeting = '';

    if (count($names) === 2) {
        $greeting .= sprintf(GREET_TEMPLATE, implode(' and ', $names));
    }
    if (count($names) > 2) {
        $lastName = array_pop($names);
        array_push($names, 'and ' . $lastName);
        $greeting .= sprintf(GREET_TEMPLATE, implode(', ', $names));
    }
    if (count($uppercase) > 0) {
        $N = reset($uppercase);
        $greeting .= sprintf(' AND ' . SHOUTING_GREET, $N);
    }

    return $greeting;
}

function handleInput(array $acc, string $n): array
{
    if (isEscaped($n)) {
        $n = trim($n, '"');
        return [...$acc, $n];
    }
    $ns = explode(', ', $n);
    return [...$acc, ...$ns];
}

function isEscaped(string $n): bool
{
    return false !== mb_strpos($n, '"');
}

function handleOneName(?string $name): string
{
    if (null === $name) {
        return sprintf(GREET_TEMPLATE, 'my friend');
    }
    if (isAllUppercase($name)) {
        return sprintf(SHOUTING_GREET, $name);
    }

    return sprintf(GREET_TEMPLATE, $name);
}

function isAllUppercase(string $name): bool
{
    return mb_strtoupper($name) === $name;
}
