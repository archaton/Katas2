<?php

declare(strict_types=1);

namespace Katas\Greeting;

function greet(?string ...$names): string
{
    if (count($names) === 1) {
        $name = reset($names);

        return handleOneName($name);
    }
    $names = array_reduce($names, static fn (array $acc, string $n): array => [...$acc, ...handleInput($n)], []);
    $uppercase = array_filter($names, static fn (string $n): bool => isAllUppercase($n));
    $names = array_filter($names, static fn (string $n): bool => !isAllUppercase($n));
    $joinedNames = '';
    if (count($names) === 2) {
        $joinedNames = implode(' and ', $names);
    }
    if (count($names) > 2) {
        $lastName = array_pop($names);
        array_push($names, 'and ' . $lastName);
        $joinedNames = implode(', ', $names);
    }

    $greeting = "Hello, {$joinedNames}.";
    if (count($uppercase) > 0) {
        $uppercaseName = reset($uppercase);
        $greeting .= " AND HELLO {$uppercaseName}!";
    }

    return $greeting;
}

function handleInput(string $n): array
{
    if (false !== mb_strpos($n, '"')) {
        return [trim($n, '"')];
    }
    return explode(', ', $n);
}

function handleOneName(?string $name): string
{
    if (null === $name) {
        return "Hello, my friend.";
    }
    if (isAllUppercase($name)) {
        return "HELLO {$name}!";
    }

    return "Hello, {$name}.";
}

function isAllUppercase(string $name): bool
{
    return mb_strtoupper($name) === $name;
}
