<?php

declare(strict_types=1);

namespace Katas;

require( __DIR__ . '/../vendor/autoload.php' );

use PHPUnit\Framework\TestCase;
use function Katas\Greeting\greet;

class GreetingTest extends TestCase
{
    /**
     * @dataProvider provideValidNameCases
     */
    public function testSuccessSimpleInterpolation(string $name)
    {
        $result = greet($name);

        $this->assertSame('Hello, '. $name, $result);
    }

    public function provideValidNameCases(): array
    {
        return [
            ['Bob'],
        ];
    }
}
