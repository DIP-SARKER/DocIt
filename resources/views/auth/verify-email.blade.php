{{-- <x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button>
                    {{ __('Resend Verification Email') }}
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout> --}}

{{-- <x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout> --}}


@extends('layouts.guest')

@section('title', 'Verify Email - DocIt')

@section('content')
    <div class="container" style="max-width: 600px;">
        <!-- Back to Home -->
        <a href="{{ route('home') }}" class="d-flex align-center gap-1 mb-3" style="color: var(--text-muted);">
            <i class="fas fa-arrow-left"></i>
            Back to home
        </a>
        <!-- Login Card -->
        <div class="card">
            <div class="mb-3  d-flex flex-column align-center">
                <a href="{{ route('home') }}" class="navbar-brand not-hover">
                    <i class="fas fa-file-alt"></i>
                    DocIt
                </a>
                <h2 class="text-center">Verify your email address</h2>
                <p class="text-muted text-center">We'll send a verification link to:
                    <span>user@email.com</span>
                </p>
                <p class="text-muted text-center">We’ve sent a verification link to:
                    user@email.com</br>Please check your inbox and click the verification link to activate your account.
                    If
                    you don’t see it, check your spam or promotions folder.</p>
            </div>

            <!-- Login Form -->
            <form class="d-flex flex-column" id="verifyEmailForm" method="POST" action="{{ route('verification.send') }}">
                @csrf
                <div class="form-group mb-0">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="you@example.com"
                        autocomplete="email" required>
                    <p class="text-muted" id="lgMailHint" style="font-size: var(--font-size-sm);">
                        <i class="fas fa-info-circle"></i>
                        Enter your valid email address.
                    </p>
                </div>
                <!-- Error Message Placeholder -->
                @if ($errors->any())
                    <div id="loginError" class="alert alert-danger align-center d-flex gap-2 mt-4">
                        <i class="fas fa-exclamation-circle"></i>
                        <div id="errorText">
                            <ul class="mb-0" style="padding-left: var(--space-sm)">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
                <button type="submit" class="btn btn-outline mt-2 form-btn">
                    <i class="fas fa-paper-plane"></i>
                    Send Email
                </button>
            </form>
        </div>
    </div>
@endsection
