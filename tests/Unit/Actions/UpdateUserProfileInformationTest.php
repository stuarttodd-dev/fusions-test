<?php

use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;
use Tests\Support\VerifiedUser;

uses(TestCase::class, RefreshDatabase::class);

it('updates profile information for a standard user', function (): void {
    $user = User::factory()->create([
        'name' => 'Old Name',
        'email' => 'old@example.com',
    ]);

    (new UpdateUserProfileInformation())->update($user, [
        'name' => 'New Name',
        'email' => 'old@example.com',
    ]);

    $user->refresh();

    expect($user->name)->toBe('New Name')
        ->and($user->email)->toBe('old@example.com');
});

it('updates verified users and re-sends verification when email changes', function (): void {
    $user = VerifiedUser::create([
        'name' => 'Old Name',
        'email' => 'old@example.com',
        'password' => bcrypt('password'),
    ]);
    $user->forceFill(['email_verified_at' => now()])->save();

    (new UpdateUserProfileInformation())->update($user, [
        'name' => 'New Name',
        'email' => 'new@example.com',
    ]);

    $notificationSent = $user->verificationNotificationSent;
    $user->refresh();

    expect($user->name)->toBe('New Name')
        ->and($user->email)->toBe('new@example.com')
        ->and($user->email_verified_at)->toBeNull()
        ->and($notificationSent)->toBeTrue();
});

it('updates the profile photo when provided', function (): void {
    Storage::fake('public');

    $user = User::factory()->create([
        'name' => 'Photo User',
        'email' => 'photo@example.com',
    ]);

    (new UpdateUserProfileInformation())->update($user, [
        'name' => 'Photo User',
        'email' => 'photo@example.com',
        'photo' => UploadedFile::fake()->image('avatar.jpg'),
    ]);

    Storage::disk('public')->assertExists($user->refresh()->profile_photo_path);
});

it('validates incoming profile information', function (): void {
    $user = User::factory()->create([
        'name' => 'Old',
        'email' => 'old@example.com',
    ]);

    expect(fn () => (new UpdateUserProfileInformation())->update($user, [
        'name' => '',
        'email' => 'not-an-email',
    ]))->toThrow(ValidationException::class);
});
