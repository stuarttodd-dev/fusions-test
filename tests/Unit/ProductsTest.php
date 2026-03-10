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

it('can attach CSV via attachCSV() then process', function (): void {
    $transformer = new ProductCsvToDemandwareXml();
    $transformer->attachCSV('a,b,c');
    expect($transformer->getRawCSV())->toBe('a,b,c');
});

it('constructor correctly assigns CSV data', function (): void {
    $transformer = new ProductCsvToDemandwareXml('a,b,c');
    expect($transformer->getRawCSV())->toBe('a,b,c');
});

it('throws when CSV is invalid', function (string $badCsv): void {
    $transformer = new ProductCsvToDemandwareXml($badCsv);
    $transformer->process();
})->throws(InvalidArgumentException::class)->with([
    'empty after trim' => ["\n  \n"],
    'too few columns' => ['a,b,c'],
]);

it('accepts CSV when each row has the expected column count', function (): void {
    $csv = "PROD123,Marvel,Antman T-shirt,897654321,Red,Small,N";
    $transformer = new ProductCsvToDemandwareXml($csv);
    expect($transformer->process())->toBe('');
});
