<?php

namespace App\Services;

use App\Contracts\CsvToXmlTransformer;
use InvalidArgumentException;

class ProductCsvProcessor
{
    public function __construct(
        private ?string $csv = null,
        private ?CsvToXmlTransformer $transformer = null
    ) {
    }

    public function attachCSV(string $csv): self
    {
        $this->csv = $csv;

        return $this;
    }

    public function getRawCSV(): ?string
    {
        return $this->csv;
    }

    public function process(): string
    {
        if ($this->csv === null || $this->csv === '') {
            throw new InvalidArgumentException('No CSV data attached. Provide CSV via constructor or attachCSV() before calling process().');
        }

        $this->validateCSV();

        if ($this->transformer === null) {
            return '';
        }

        return $this->transformer->transform($this->csv);
    }

    private function validateCSV(): void
    {
        $lines = array_filter(explode("\n", trim($this->csv)));
        if (count($lines) === 0) {
            throw new InvalidArgumentException('Invalid CSV: no data.');
        }

        $expectedCount = config('product_csv.column_count', 7);
        foreach ($lines as $line) {
            $row = str_getcsv($line);
            if (count($row) !== $expectedCount) {
                throw new InvalidArgumentException("Invalid CSV: each row must have {$expectedCount} columns.");
            }
        }
    }
}
