<?php

use App\Console\Kernel as AppKernel;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

uses(TestCase::class);

it('exposes scheduled commands without registering defaults', function (): void {
    $kernel = app(AppKernel::class);
    $schedule = app(Schedule::class);

    \Closure::bind(function () use ($schedule): void {
        $this->schedule($schedule);
    }, $kernel, AppKernel::class)();

    expect($schedule->events())->toBe([]);
});

it('loads console commands', function (): void {
    $kernel = app(AppKernel::class);

    \Closure::bind(function (): void {
        $this->commands();
    }, $kernel, AppKernel::class)();

    expect(array_key_exists('inspire', Artisan::all()))->toBeTrue();
});
