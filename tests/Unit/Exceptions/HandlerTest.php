<?php

use App\Exceptions\Handler;
use Sentry\EventHint;
use Sentry\SentrySdk;
use Tests\TestCase;

uses(TestCase::class)->beforeEach(function (): void {
    $this->originalHub = SentrySdk::getCurrentHub();
})->afterEach(function (): void {
    SentrySdk::setCurrentHub($this->originalHub);
    \Mockery::close();
});

it('reports exceptions to Sentry', function (): void {
    $captured = false;

    $mockHub = \Mockery::mock(\Sentry\State\HubInterface::class);
    $mockHub->shouldReceive('captureException')
        ->atLeast()->once()
        ->withArgs(function ($exception, $hint) {
            return $exception instanceof \Exception
                && $hint instanceof EventHint;
        })
        ->andReturnUsing(function () use (&$captured): void {
            $captured = true;
        });

    SentrySdk::setCurrentHub($mockHub);

    $handler = app(Handler::class);
    $handler->register();

    $handler->report(new \Exception('boom'));

    expect($captured)->toBeTrue();
});
