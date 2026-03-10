<?php

use App\Services\DemandwareTransformer;
use App\Services\ProductCsvProcessor;
use Tests\TestCase;

uses(TestCase::class);

it('outputs Demandware XML that matches expected fixture when given fixture CSV', function (): void {
    $csv = file_get_contents(__DIR__ . '/../fixtures/product-data.csv');
    $expectedXml = file_get_contents(__DIR__ . '/../fixtures/expected-output.xml');

    $processor = new ProductCsvProcessor($csv, new DemandwareTransformer());
    $actualXml = $processor->process();

    expect(normaliseXml($actualXml))->toBe(normaliseXml($expectedXml));
});

it('throws when process() is called without CSV attached', function (): void {
    $processor = new ProductCsvProcessor();
    $processor->process();
})->throws(InvalidArgumentException::class);

it('throws when process() is called with empty CSV content', function (): void {
    $processor = new ProductCsvProcessor('');
    $processor->process();
})->throws(InvalidArgumentException::class);

it('produces valid catalog XML for a single product with one variant')->skip();

it('can attach CSV via attachCSV() then process', function (): void {
    $processor = new ProductCsvProcessor();
    $processor->attachCSV('a,b,c');
    expect($processor->getRawCSV())->toBe('a,b,c');
});

it('constructor correctly assigns CSV data', function (): void {
    $processor = new ProductCsvProcessor('a,b,c');
    expect($processor->getRawCSV())->toBe('a,b,c');
});

it('throws when CSV is invalid', function (string $badCsv): void {
    $processor = new ProductCsvProcessor($badCsv);
    $processor->process();
})->throws(InvalidArgumentException::class)->with([
    'empty after trim' => ["\n  \n"],
    'too few columns' => ['a,b,c'],
]);

it('accepts CSV when each row has the expected column count', function (): void {
    $csv = "PROD123,Marvel,Antman T-shirt,897654321,Red,Small,N";
    $processor = new ProductCsvProcessor($csv);
    expect($processor->process())->toBe('');
});
