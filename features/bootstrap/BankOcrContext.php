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
        private int $charsCountPerLine,
        private int $numberLength,
    )
    {
        $this->decipherService = new DecipherService(
            $linesNb,
            $charsCountPerLine,
            $numberLength,
        );
    }

    /**
     * @Given there is a single number :number with digit:
     */
    public function thereIsASingleNumberWithDigit($number, PyStringNode $digit)
    {
        $preprocessedDigitString = $this->decipherService->preprocess($digit);
        $result = $this->decipherService->readSingleDigit($preprocessedDigitString);
        Assert::assertSame(
            $number,
            $result,
        );
    }

    /**
     * @Given there is number :number with digits:
     */
    public function thereIsNumberWithDigits($number, PyStringNode $digits)
    {
        $preprocessedDigitsString = $this->decipherService->preprocess($digits);
        dump($preprocessedDigitsString);
        $result = $this->decipherService->readEntry($preprocessedDigitsString);
        dump($number, $result);
        Assert::assertSame(
            $number,
            $result,
        );
    }
}
