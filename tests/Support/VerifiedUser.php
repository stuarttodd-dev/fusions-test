<?php

namespace Tests\Support;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class VerifiedUser extends User implements MustVerifyEmail
{
    public bool $verificationNotificationSent = false;

    protected $table = 'users';

    public function sendEmailVerificationNotification(): void
    {
        $this->verificationNotificationSent = true;
    }
}
