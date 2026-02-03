@extends('layouts.guest')

@section('title', 'Login - DocIt')

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
                <h2 class="text-center">Welcome back</h2>
                <p class="text-muted text-center">Sign in to access your tasks, documents, and links</p>
            </div>

            <!-- Security Alert -->
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle"></i>
                <strong>Security Tip:</strong> When using public computers, enable auto-logout in settings.
            </div>

            <!-- Login Form -->
            <form class="d-flex flex-column" id="loginForm" method="POST" action="{{ route('login') }}">
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

                <div class="form-group">
                    <div class="d-flex justify-between">
                        <label for="password" class="form-label">Password</label>
                        <a class="not-hover" href="{{ route('password.request') }}"
                            style="font-size: var(--font-size-sm);">Forgot
                            password?</a>
                    </div>
                    <div style="position: relative;">
                        <input type="password" id="password" name="password" class="form-control"
                            placeholder="Enter your password" autocomplete="current-password" required>
                        <button type="button" id="togglePassword" class="i-btn"
                            style="position: absolute; right: 0; top: 0; height: 100%; color: var(--text-muted); border: none; background: transparent; margin-right: 15px; cursor: pointer;">
                            <i class="far fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="form-check">
                    <input type="checkbox" id="remember" name="remember" class="form-check-input" value="1">
                    <label for="remember" class="form-check-label">Remember me on this device</label>
                </div>

                <!-- Error Message Placeholder -->
                @if ($errors->any() && !str_contains($errors->first('email'), 'banned'))
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
                @if ($errors->has('email') && str_contains($errors->first('email'), 'banned'))
                    <div class="alert alert-warning align-center d-flex gap-2 mt-4">
                        <i class="fas fa-ban"></i>
                        <div>
                            Your account has been banned. Please <a href="mailto:contact.dipsdevs@gmail.com">contact
                                support</a>.
                        </div>
                    </div>
                @endif
                <div class="d-flex justify-center">
                    <div class="cf-turnstile" data-sitekey="{{ config('services.turnstile.sitekey') }}">
                    </div>
                </div>
                <button type="submit" class="btn btn-outline mt-2 form-btn">
                    <i class="fas fa-sign-in-alt"></i>
                    Sign In
                </button>
            </form>

            <div class="text-center mt-3">
                <p class="text-muted">Don't have an account? <a href="{{ route('register') }}">Create one now</a></p>
            </div>

            <div class="text-center mt-3">
                <p class="text-muted" style="font-size: var(--font-size-sm);">
                    <i class="fas fa-lock"></i>
                    Your data is securely encrypted and we never store your passwords.
                </p>
            </div>
        </div>
    </div>
@endsection
