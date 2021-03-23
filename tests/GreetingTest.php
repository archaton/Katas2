<?php

declare(strict_types=1);

namespace Katas;

require( __DIR__ . '/../vendor/autoload.php' );

use PHPUnit\Framework\TestCase;
use function Katas\Greeting\greet;

class GreetingTest extends TestCase
{
    /**
     * @dataProvider provideGreetingCases
     */
    public function testSuccessGreeting(string $expectedGreeting, ?string ...$name)
    {
        $result = greet(...$name);

        $this->assertSame($expectedGreeting, $result);
    }

    public function provideGreetingCases(): array
    {
        return [
            'Requirement 1' => ["Hello, Bob.", "Bob"],
            'Requirement 2' => ["Hello, my friend.", null],
            'Requirement 3' => ["HELLO JERRY!", "JERRY"],
            'Requirement 4' => ["Hello, Jill and Jane.", "Jill", "Jane"],
            'Requirement 5' => ["Hello, Amy, Brian, and Charlotte.", "Amy", "Brian", "Charlotte"],
            'Requirement 6' => ["Hello, Amy and Charlotte. AND HELLO BRIAN!", "Amy", "BRIAN", "Charlotte"],
            'Requirement 7' => ["Hello, Bob, Charlie, and Dianne.", "Bob", "Charlie, Dianne"],
            'Requirement 8' => ["Hello, Bob and Charlie, Dianne.", "Bob", "\"Charlie, Dianne\""],
        ];
    }
}
