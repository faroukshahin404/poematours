<?php

namespace App\Repositories\Dashboard\Admin;

use App\Contracts\Repositories\Dashboard\Admin\AdminAuthRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminAuthRepository implements AdminAuthRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function attemptAdminLogin(array $credentials): bool
    {
        $remember = (bool) ($credentials['remember'] ?? false);

        if (! Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
        ], $remember)) {
            return false;
        }

        $user = Auth::user();
        if (! $user instanceof User || ! $user->is_admin) {
            Auth::logout();

            return false;
        }

        return true;
    }
}
