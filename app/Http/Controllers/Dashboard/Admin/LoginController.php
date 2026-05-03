<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Contracts\Repositories\Dashboard\Admin\AdminAuthRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Admin\AdminLoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class LoginController extends Controller
{
    public function __construct(
        private readonly AdminAuthRepositoryInterface $adminAuthRepository
    ) {}

    /**
     * Show the admin login page.
     */
    public function create(): Response
    {
        return Inertia::render('Dashboard/Admin/Login');
    }

    /**
     * Handle an incoming admin authentication request.
     */
    public function store(AdminLoginRequest $request): RedirectResponse
    {
        if (! $this->adminAuthRepository->attemptAdminLogin($request->credentials())) {
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    /**
     * Destroy an authenticated admin session.
     */
    public function destroy(): RedirectResponse
    {
        auth()->logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
