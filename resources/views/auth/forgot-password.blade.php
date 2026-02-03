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

@section('title', 'Forgot Password - DocIt')

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
                <h2 class="text-center">Forgot your password?</h2>
                <p class="text-muted text-center">No worries. Enter your email and we’ll send you a reset link.</p>
            </div>

            <!-- Login Form -->
            <form class="d-flex flex-column" id="forgotPassForm" method="POST" action="{{ route('password.email') }}">
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
