<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {

        // ✅ Turnstile validation (before authenticate)
        $request->validate([
            'cf-turnstile-response' => ['required'],
        ], [
            'cf-turnstile-response.required' => 'Please verify you are human.',
        ]);

        $verify = Http::asForm()->post(
            'https://challenges.cloudflare.com/turnstile/v0/siteverify',
            [
                'secret' => config('services.turnstile.secret'),
                'response' => $request->input('cf-turnstile-response'),
                // 'remoteip' => $request->ip(), // optional
            ]
        );

        if (!$verify->ok() || data_get($verify->json(), 'success') !== true) {
            throw ValidationException::withMessages([
                'cf-turnstile-response' => 'Turnstile verification failed. Please try again.',
            ]);
        }

        $request->authenticate();
        if (auth()->user()->is_banned) {
            Auth::guard('web')->logout();
            throw ValidationException::withMessages([
                'email' => 'Your account has been banned. Please contact support.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
