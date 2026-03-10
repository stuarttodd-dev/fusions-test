<?php

namespace App\Contracts;

interface CsvToXmlTransformer
{
    public function transform(string $csv): string;
}
