<?php

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\EncryptCookies;
use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\PreventRequestsDuringMaintenance;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\TrustHosts;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\AlwaysProp;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class)->afterEach(fn () => \Mockery::close());

beforeEach(function (): void {
    Route::get('/login', fn () => 'login')->name('login');
});

it('redirects unauthenticated users to login', function (): void {
    $middleware = new Authenticate(app('auth'));
    $request = Request::create('/protected');

    $redirect = \Closure::bind(function () use ($request) {
        return $this->redirectTo($request);
    }, $middleware, Authenticate::class)();

    expect($redirect)->toBe(route('login'));
});

it('returns null for json unauthenticated requests', function (): void {
    $middleware = new Authenticate(app('auth'));
    $request = Request::create('/api/protected');
    $request->headers->set('Accept', 'application/json');

    $redirect = \Closure::bind(function () use ($request) {
        return $this->redirectTo($request);
    }, $middleware, Authenticate::class)();

    expect($redirect)->toBeNull();
});

it('exposes encrypt cookies configuration', function (): void {
    $middleware = new EncryptCookies(app('encrypter'));

    $except = Closure::bind(function () {
        return $this->except;
    }, $middleware, EncryptCookies::class)();

    expect($except)->toBeArray()->and($except)->toBeEmpty();
});

it('keeps maintenance whitelist empty by default', function (): void {
    $middleware = new PreventRequestsDuringMaintenance(app());

    $except = Closure::bind(function () {
        return $this->except;
    }, $middleware, PreventRequestsDuringMaintenance::class)();

    expect($except)->toBeArray()->and($except)->toBeEmpty();
});

it('redirects authenticated users away from guest routes', function (): void {
    Route::get('/dashboard', fn () => 'dashboard')->name('dashboard');

    Auth::shouldReceive('guard')->with(null)->andReturn(new class {
        public function check(): bool
        {
            return true;
        }
    });

    $middleware = new RedirectIfAuthenticated();

    $response = $middleware->handle(
        Request::create('/login'),
        fn () => new Response('next')
    );

    expect($response)->toBeInstanceOf(RedirectResponse::class)
        ->and($response->getTargetUrl())->toBe(url(RouteServiceProvider::HOME));
});

it('allows guests to proceed when not authenticated', function (): void {
    Auth::shouldReceive('guard')->with(null)->andReturn(new class {
        public function check(): bool
        {
            return false;
        }
    });

    $middleware = new RedirectIfAuthenticated();

    $response = $middleware->handle(
        Request::create('/login'),
        fn () => new Response('next')
    );

    expect($response->getContent())->toBe('next');
});

it('returns trusted hosts for the application domain', function (): void {
    $originalUrl = config('app.url');
    config(['app.url' => 'https://example.com']);
    \Illuminate\Http\Middleware\TrustHosts::flushState();

    $middleware = app(TrustHosts::class);

    expect($middleware->hosts())->toEqual(['^(.+\.)?example\.com$']);

    config(['app.url' => $originalUrl]);
    \Illuminate\Http\Middleware\TrustHosts::flushState();
});

it('shares inertia defaults and honours asset versioning', function (): void {
    $middleware = new HandleInertiaRequests();
    $request = Request::create('/');

    $shared = $middleware->share($request);

    expect($shared)->toHaveKey('errors')
        ->and($shared['errors'])->toBeInstanceOf(AlwaysProp::class)
        ->and($middleware->rootView($request))->toBe('app');

    $originalAssetUrl = config('app.asset_url');
    config(['app.asset_url' => 'https://assets.example.com']);

    $expectedVersion = hash('xxh128', 'https://assets.example.com');
    expect($middleware->version($request))->toBe($expectedVersion);

    config(['app.asset_url' => $originalAssetUrl]);
});
