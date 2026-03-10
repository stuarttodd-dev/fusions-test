<?php

namespace App\Services;

use InvalidArgumentException;

class ProductCsvToDemandwareXml
{
    public function __construct(private ?string $csv = null)
    {
        //
    }

    public function attachCSV(string $csv): self
    {
        $this->csv = $csv;

        return $this;
    }

    public function process(): string
    {
        if ($this->csv === null || $this->csv === '') {
            throw new InvalidArgumentException('No CSV data attached. Provide CSV via constructor or attachCSV() before calling process().');
        }

        return '';
    }
}
