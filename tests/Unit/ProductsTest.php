<?php

use App\Services\ProductCsvToDemandwareXml;
use Tests\TestCase;

uses(TestCase::class);

it('outputs Demandware XML that matches expected fixture when given fixture CSV')->skip();

it('throws when process() is called without CSV attached', function (): void {
    $transformer = new ProductCsvToDemandwareXml();
    $transformer->process();
})->throws(InvalidArgumentException::class);

it('throws when process() is called with empty CSV content', function (): void {
    $transformer = new ProductCsvToDemandwareXml('');
    $transformer->process();
})->throws(InvalidArgumentException::class);

it('produces valid catalog XML for a single product with one variant')->skip();

it('can attach CSV via attachCSV() then process')->skip();
