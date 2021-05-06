<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Katas\BankOcr\DecipherService;
use PHPUnit\Framework\Assert;

class BankOcrContext implements Context
{
    private DecipherService $decipherService;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct(
        private int $linesNb,
    )
    {
        $this->decipherService = new DecipherService(
            $linesNb
        );
    }

    /**
     * @Given there is number :number with digits:
     */
    public function thereIsNumberWithDigits($number, PyStringNode $digits)
    {
        Assert::assertSame(
            $number,
            $this->decipherService->readEntry($digits->getRaw()),
        );
    }
}
