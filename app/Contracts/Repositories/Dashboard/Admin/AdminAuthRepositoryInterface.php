<?php

namespace App\Contracts\Repositories\Dashboard\Admin;

interface AdminAuthRepositoryInterface
{
    /**
     * Attempt session authentication for an admin user.
     *
     * @param  array{email: string, password: string, remember?: bool}  $credentials
     */
    public function attemptAdminLogin(array $credentials): bool;
}
