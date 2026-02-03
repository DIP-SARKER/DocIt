<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Http;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
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
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'min:6', 'alpha_dash', 'unique:' . User::class],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
