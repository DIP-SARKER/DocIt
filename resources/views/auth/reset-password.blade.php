{{-- <x-guest-layout>
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Reset Password') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout> --}}

@extends('layouts.guest')

@section('title', 'Reset Password - DocIt')

@section('content')
    <div class="container" style="max-width: 600px;">
        <!-- Back to Home -->
        <a href="{{ route('home') }}" class="d-flex align-center gap-1 mb-3" style="color: var(--text-muted);">
            <i class="fas fa-arrow-left"></i>
            Back to home
        </a>
        <!-- Register Card -->
        <div class="card">
            <div class="mb-3  d-flex flex-column align-center">
                <a href="{{ route('home') }}" class="navbar-brand not-hover">
                    <i class="fas fa-file-alt"></i>
                    DocIt
                </a>
                <h2 class="text-center">Set up a new password</h2>
            </div>

            <!-- Register Form -->
            <form class="d-flex flex-column" id="resetPassForm" method="POST" action="{{ route('password.store') }}">
                @csrf

                <div class="form-group mb-0">
                    <label for="password" class="form-label">Password</label>
                    <div style="position: relative;">
                        <input type="password" id="password" name="password" class="form-control"
                            placeholder="Create a strong password" autocomplete="new-password" required>
                        <button type="button" id="togglePassword" class="i-btn"
                            style="position: absolute; right: 0; top: 0; height: 100%; color: var(--text-muted); border: none; background: transparent; margin-right: 15px; cursor: pointer;">
                            <i class="far fa-eye"></i>
                        </button>
                    </div>
                    <p class="text-muted" id="hintPass"
                        style="font-size: var(--font-size-sm); margin-top: var(--space-xs);">
                        <i class="fas fa-info-circle"></i>
                        Min. 8 chars, include upper/lowercase, numbers, symbols.
                    </p>
                </div>

                <div class="form-group mb-0">
                    <label for="confirmPassword" class="form-label">Confirm Password</label>
                    <div style="position: relative;">
                        <!-- ✅ FIXED NAME (NO SPACES, NO LINE BREAK) -->
                        <input type="password" name="password_confirmation" id="confirmPassword" class="form-control"
                            placeholder="Confirm your password" autocomplete="new-password" required>
                        <button type="button" id="toggleCP" class="i-btn"
                            style="position: absolute; right: 0; top: 0; height: 100%; color: var(--text-muted); border: none; background: transparent; margin-right: 15px; cursor: pointer;">
                            <i class="far fa-eye"></i>
                        </button>
                    </div>
                    <p class="text-muted" id="hintCP"
                        style="font-size: var(--font-size-sm); margin-top: var(--space-xs);">
                        <i class="fas fa-info-circle"></i>
                        Ensure this matches your chosen password.
                    </p>
                </div>

                @if ($errors->any())
                    <div id="registerError" class="alert alert-danger align-center d-flex gap-2 mt-4">
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

                <!-- ✅ Remove disabled unless JS enables it -->
                <button type="submit" id="registerSubmitBtn" class="btn btn-outline mt-2 form-btn">
                    <i class="fas fa-wrench"></i>
                    Update Password
                </button>
            </form>
        </div>
    </div>
@endsection
