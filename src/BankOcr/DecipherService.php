<?php

declare(strict_types=1);


namespace Katas\BankOcr;


class DecipherService
{
    private array $alternatives;

    public function __construct(
        private int $linesNb,
        private int $charsCountPerLine,
        private int $numberLength,
        private int $digitCharsLength,
    )
    {
        $this->alternatives = [];
    }

    public function readSingleEntry(string $entry): string
    {
        $digits = $this->extractDigits($entry);

        return $this->mapExtractedDigits($digits);
    }

    public function readSingleDigit(string $digit): string
    {
        $deflated = $this->inline($digit);

        return match ($deflated) {
            " _ " .
            "| |" .
            "|_|" => '0',
            "   " .
            "  |" .
            "  |" => '1',
            " _ " .
            " _|" .
            "|_ " => '2',
            " _ " .
            " _|" .
            " _|" => '3',
            "   " .
            "|_|" .
            "  |" => '4',
            " _ " .
            "|_ " .
            " _|" => '5',
            " _ " .
            "|_ " .
            "|_|" => '6',
            " _ " .
            "  |" .
            "  |" => '7',
            " _ " .
            "|_|" .
            "|_|" => '8',
            " _ " .
            "|_|" .
            " _|" => '9',
            default => '?',
        };
    }

    public function inline(string $digit): string
    {
        return str_replace("\n", '', $digit);
    }

    public function preprocess(string $digit): string
    {
        $digit = rtrim($digit, "\n");
        return str_replace(['^', '$'], '', $digit);
    }

    public function isValidAccountNumber(string $result): bool
    {
        $wages = [9, 8, 7, 6, 5, 4, 3, 2, 1];
        $digits = mb_str_split($result);
        assert($this->numberLength === count($digits));
        $checksum = 0;
        for ($i = 0; $i < $this->numberLength; ++$i) {
            if ('?' === $digits[$i]) {
                return false;
            }
            $checksum += $digits[$i] * $wages[$i];
        }
        return 0 === ($checksum % 11);
    }

    public function getOutputWithStatus(string $preprocessedDigitsString): string
    {
        $number = $this->readSingleEntry($preprocessedDigitsString);
        if (str_contains($number, '?')) {
            return $number . ' ILL';
        }
        if (!$this->isValidAccountNumber($number)) {
            return $number . ' ERR';
        }
        return $number;
    }

    /**
     * @throws AmbivalentResultException
     */
    private function guessSingleEntry(string $entry): string
    {
        $this->alternatives = [];
        $digits = $this->extractDigits($entry);
        $patchesMap = [
            ' ' => ['_', '|'],
            '_' => [' '],
            '|' => [' '],
        ];

        foreach ($digits as $digitPosition => $digit) {
            $pieces = mb_str_split($digit);
            foreach ($pieces as $piecePosition => $piece) {
                foreach ($patchesMap[$piece] as $patch) {
                    $piecesCopy = $pieces;
                    $piecesCopy[$piecePosition] = $patch;
                    $digitCopy = implode('', $piecesCopy);
                    $singleDigit = $this->readSingleDigit($digitCopy);
                    if ('?' === $singleDigit) {
                        continue;
                    }
                    $digitsCopy = $digits;
                    $digitsCopy[$digitPosition] = $digitCopy;
                    $accNumber = $this->mapExtractedDigits($digitsCopy);
                    if ($this->isValidAccountNumber($accNumber)) {
                        $this->alternatives[] = $accNumber;
                        continue;
                    }
                }
            }
        }
        $solutionsCount = count($this->alternatives);
        if ($solutionsCount === 0) {
            return $this->mapExtractedDigits($digits);
        }
        if ($solutionsCount === 1) {
            return $this->alternatives[0];
        }

        throw new AmbivalentResultException();
    }

    public function guessOutput(string $preprocessedDigitsString): string
    {
        $statusOutput = $this->getOutputWithStatus($preprocessedDigitsString);
        if (mb_strlen($statusOutput) === $this->numberLength) {
            return $statusOutput;
        }
        if (str_contains($statusOutput, 'ERR')) {
            try {
                return $this->guessSingleEntry($preprocessedDigitsString);
            } catch (AmbivalentResultException $resultException) {
                return strtr($statusOutput, ['ERR' => 'AMB']);
            }
        }
        if (str_contains($statusOutput, 'ILL')) {
            return $this->guessSingleEntry($preprocessedDigitsString);
        }

        return $statusOutput;
    }

    private function extractDigits(string $entry): array
    {
        $digits = [];
        $lines = explode("\n", $entry);
        foreach ($lines as $lineNb => $line) {
            $line = str_pad($line, $this->charsCountPerLine);
            foreach (mb_str_split($line, $this->digitCharsLength) as $strideNb => $stride) {
                $digits[$strideNb] = ($digits[$strideNb] ?? '') . $stride;
            }
        }
        return $digits;
    }

    /**
     * @param array $digits
     * @return string
     */
    private function mapExtractedDigits(array $digits): string
    {
        $mappedSingleDigits = array_map([$this, 'readSingleDigit'], $digits);
        return implode('', $mappedSingleDigits);
    }

    public function getResultAlternatives(): array
    {
        return $this->alternatives;
    }
}
