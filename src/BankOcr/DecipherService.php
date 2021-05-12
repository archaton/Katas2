<?php

declare(strict_types=1);


namespace Katas\BankOcr;


class DecipherService
{
    public function __construct(
        private int $linesNb,
        private int $charsCountPerLine,
        private int $numberLength,
    )
    {
    }

    public function readSingleEntry(string $entry): string
    {
        $digits = [];
        $lines = explode("\n", $entry);
        foreach ($lines as $lineNb => $line) {
            $line = str_pad($line, $this->charsCountPerLine);
            foreach (mb_str_split($line, 3) as $strideNb => $stride) {
                $digits[$strideNb] = ($digits[$strideNb] ?? '') . $stride;
            }
        }

        $mappedSingleDigits = array_map([$this, 'readSingleDigit'], $digits);
        return implode('', $mappedSingleDigits);
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
}
