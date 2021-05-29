<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Katas\BankOcr\DecipherService;
use PHPUnit\Framework\Assert;

class BankOcrContext implements Context
{
    private const LINES_NB = 4;
    private const CHARS_COUNT_PER_LINE = 27;
    private const NUMBER_LENGTH = 9;
    private const DIGIT_CHARS_LENGTH = 3;

    private DecipherService $decipherService;
    private string $givenDigits;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct(
    )
    {
        $this->alternatives = [];
        $this->decipherService = new DecipherService(
            self::LINES_NB,
            self::CHARS_COUNT_PER_LINE,
            self::NUMBER_LENGTH,
            self::DIGIT_CHARS_LENGTH,
        );
    }

    /**
     * @Given there is a single number :number with digit:
     */
    public function thereIsASingleNumberWithDigit($number, PyStringNode $digit)
    {
        $digitCharsCountWithSpecialStartMark = self::DIGIT_CHARS_LENGTH + 1;
        $digit = new PyStringNode(array_map(
            static fn (string $line): string => str_pad($line, $digitCharsCountWithSpecialStartMark),
            $digit->getStrings()
        ), $digit->getLine());
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
        $result = $this->decipherService->readSingleEntry($preprocessedDigitsString);
        Assert::assertSame(
            $number,
            $result,
        );
    }

    /**
     * @Given there is a valid account number :number with digits:
     */
    public function thereIsAValidAccountNumberWithDigits($number, PyStringNode $digits)
    {
        $preprocessedDigitsString = $this->decipherService->preprocess($digits);
        $result = $this->decipherService->readSingleEntry($preprocessedDigitsString);
        Assert::assertSame(
            $number,
            $result,
        );
        Assert::assertTrue($this->decipherService->isValidAccountNumber($result));
    }

    /**
     * @Given there is an invalid account number :number with digits:
     */
    public function thereIsAnInvalidAccountNumberWithDigits($number, PyStringNode $digits)
    {
        $preprocessedDigitsString = $this->decipherService->preprocess($digits);
        $result = $this->decipherService->readSingleEntry($preprocessedDigitsString);
        Assert::assertSame(
            $number,
            $result,
        );
        Assert::assertFalse($this->decipherService->isValidAccountNumber($result));
    }

    /**
     * @Given there is output :output with with digits:
     */
    public function thereIsOutputWithWithDigits($output, PyStringNode $digits)
    {
        $preprocessedDigitsString = $this->decipherService->preprocess($digits);
        $result = $this->decipherService->getOutputWithStatus($preprocessedDigitsString);
        Assert::assertSame(
            $output,
            $result,
        );
    }

    /**
     * @Given guessed output :expected with digits:
     */
    public function guessedOutputWithDigits($expected, PyStringNode $digits)
    {
        $preprocessedDigitsString = $this->decipherService->preprocess($digits);
        $result = $this->decipherService->guessOutput($preprocessedDigitsString);
        dump('$expected', $expected, '$result', $result);
        Assert::assertSame(
            $expected,
            $result,
        );
    }

    /**
     * @Given digits:
     */
    public function digits(PyStringNode $digits)
    {
        $preprocessedDigitsString = $this->decipherService->preprocess($digits);
        $this->givenDigits = $preprocessedDigitsString;
    }

    /**
     * @Then guessed output is :expected
     */
    public function guessedOutputIs($expected)
    {
        $result = $this->decipherService->guessOutput($this->givenDigits);
        Assert::assertSame(
            $expected,
            $result,
        );
    }

    /**
     * @Given possible alternatives are:
     */
    public function possibleAlternativesAre(TableNode $expectedAlternatives)
    {
        $expected = array_column($expectedAlternatives->getColumnsHash(), 'number');
        $alternatives = $this->decipherService->getResultAlternatives();
        Assert::assertSame([], array_diff($alternatives, $expected));
        Assert::assertSame([], array_diff($expected, $alternatives));
    }
}
