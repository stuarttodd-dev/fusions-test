<?php

use App\View\Components\AppLayout;
use App\View\Components\GuestLayout;
use Tests\TestCase;

uses(TestCase::class);

it('renders the app layout view', function (): void {
    $component = new AppLayout();

    expect($component->render()->name())->toBe('layouts.app');
});

it('renders the guest layout view', function (): void {
    $component = new GuestLayout();

    expect($component->render()->name())->toBe('layouts.guest');
});
