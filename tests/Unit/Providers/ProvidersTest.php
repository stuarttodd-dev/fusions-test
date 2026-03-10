<?php

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Providers\AppServiceProvider;
use App\Providers\BroadcastServiceProvider;
use App\Providers\FortifyServiceProvider;
use App\Providers\RouteServiceProvider;
use App\Providers\TelescopeServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Fortify\Contracts\RedirectsIfTwoFactorAuthenticatable;
use Laravel\Fortify\Contracts\ResetsUserPasswords;
use Laravel\Fortify\Contracts\UpdatesUserPasswords;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;
use Laravel\Telescope\IncomingEntry;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable as RedirectIfTwoFactorAuthenticatableAction;
use Laravel\Telescope\Telescope;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class)->afterEach(fn () => \Mockery::close());

it('registers telescope while in local environment', function (): void {
    $originalEnv = App::environment();
    app()->instance('env', 'local');

    (new AppServiceProvider(app()))->register();

    expect(app()->getProviders(TelescopeServiceProvider::class))->not()->toBeEmpty();

    app()->instance('env', $originalEnv);
});

it('boots broadcast routes and channels', function (): void {
    $routesCalled = false;
    Broadcast::shouldReceive('routes')
        ->once()
        ->andReturnUsing(function () use (&$routesCalled): void {
            $routesCalled = true;
        });
    Broadcast::shouldReceive('channel')
        ->once()
        ->with('App.Models.User.{id}', \Mockery::type('Closure'))
        ->andReturnTrue();

    (new BroadcastServiceProvider(app()))->boot();

    expect($routesCalled)->toBeTrue();
});

it('registers fortify callbacks and rate limiters', function (): void {
    (new FortifyServiceProvider(app()))->boot();

    expect(app(CreatesNewUsers::class))->toBeInstanceOf(CreateNewUser::class);
    expect(app(UpdatesUserProfileInformation::class))->toBeInstanceOf(UpdateUserProfileInformation::class);
    expect(app(UpdatesUserPasswords::class))->toBeInstanceOf(UpdateUserPassword::class);
    expect(app(ResetsUserPasswords::class))->toBeInstanceOf(ResetUserPassword::class);
    expect(app(RedirectsIfTwoFactorAuthenticatable::class))
        ->toBeInstanceOf(RedirectIfTwoFactorAuthenticatableAction::class);

    $loginLimiter = RateLimiter::limiter('login');
    $loginRequest = Request::create('/login', 'POST', ['email' => 'user@example.com']);
    $loginRequest->server->set('REMOTE_ADDR', '127.0.0.1');
    $limit = $loginLimiter($loginRequest);
    expect($limit)->toBeInstanceOf(Limit::class)
        ->and($limit->maxAttempts)->toBe(5);

    $twoFactorLimiter = RateLimiter::limiter('two-factor');
    $twoFactorRequest = Request::create('/two-factor');
    $session = app('session')->driver();
    $session->start();
    $twoFactorRequest->setLaravelSession($session);
    $twoFactorRequest->session()->put('login.id', 'foo');
    $twoFactorLimit = $twoFactorLimiter($twoFactorRequest);
    expect($twoFactorLimit)->toBeInstanceOf(Limit::class)
        ->and($twoFactorLimit->maxAttempts)->toBe(5);
});

it('configures api rate limiter and route groups', function (): void {
    (new RouteServiceProvider(app()))->boot();

    $limiter = RateLimiter::limiter('api');
    $apiRequest = Request::create('/api/user');
    $apiRequest->server->set('REMOTE_ADDR', '127.0.0.1');
    $apiRequest->setUserResolver(fn () => null);
    $limit = $limiter($apiRequest);
    expect($limit)->toBeInstanceOf(Limit::class)
        ->and($limit->maxAttempts)->toBe(60);
});

it('registers telescope filters and hides sensitive data in production', function (): void {
    $originalEnv = App::environment();
    app()->instance('env', 'production');

    $originalHiddenParameters = Telescope::$hiddenRequestParameters;
    $originalHiddenHeaders = Telescope::$hiddenRequestHeaders;
    $originalFilters = Telescope::$filterUsing;

    (new TelescopeServiceProvider(app()))->register();

    expect(Telescope::$hiddenRequestParameters)->toContain('_token')
        ->and(Telescope::$hiddenRequestHeaders)->toContain('cookie')
        ->and(Telescope::$filterUsing)->not()->toBeEmpty();

    Telescope::$hiddenRequestParameters = $originalHiddenParameters;
    Telescope::$hiddenRequestHeaders = $originalHiddenHeaders;
    Telescope::$filterUsing = $originalFilters;

    app()->instance('env', $originalEnv);
});

it('skips hiding sensitive request details in local environment', function (): void {
    $originalEnv = App::environment();
    app()->instance('env', 'local');

    $originalHiddenParameters = Telescope::$hiddenRequestParameters;
    $originalHiddenHeaders = Telescope::$hiddenRequestHeaders;
    $originalFilters = Telescope::$filterUsing;

    (new TelescopeServiceProvider(app()))->register();

    expect(Telescope::$hiddenRequestParameters)->toEqual($originalHiddenParameters)
        ->and(Telescope::$hiddenRequestHeaders)->toEqual($originalHiddenHeaders);

    $entry = \Mockery::mock(IncomingEntry::class);
    $entry->shouldReceive('isReportableException')->andReturn(false);
    $entry->shouldReceive('isFailedRequest')->andReturn(false);
    $entry->shouldReceive('isFailedJob')->andReturn(false);
    $entry->shouldReceive('isScheduledTask')->andReturn(false);
    $entry->shouldReceive('hasMonitoredTag')->andReturn(false);

    $filter = end(Telescope::$filterUsing);
    expect($filter($entry))->toBeTrue();

    Telescope::$hiddenRequestParameters = $originalHiddenParameters;
    Telescope::$hiddenRequestHeaders = $originalHiddenHeaders;
    Telescope::$filterUsing = $originalFilters;

    app()->instance('env', $originalEnv);
});

it('defines a gate for viewing telescope', function (): void {
    $provider = new TelescopeServiceProvider(app());

    \Closure::bind(function (): void {
        $this->gate();
    }, $provider, TelescopeServiceProvider::class)();

    $user = new class {
        public string $email = 'person@example.com';
    };

    expect(Gate::has('viewTelescope'))->toBeTrue()
        ->and(Gate::forUser($user)->allows('viewTelescope'))->toBeFalse();
});
