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
            <div class="text-center mb-3">
                <div class="navbar-brand"
                    style="font-size: var(--font-size-2xl); justify-content: center; margin-bottom: var(--space-sm);">
                    <i class="fas fa-file-alt"></i>
                    DocIt
                </div>
                <h2>Welcome back</h2>
                <p class="text-muted">Sign in to access your tasks, documents, and links</p>
            </div>

            <!-- Security Alert -->
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                <strong>Security Tip:</strong> When using public computers, enable auto-logout in settings.
            </div>

            <!-- Login Form -->
            <form id="loginForm" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="you@example.com"
                        required>
                </div>

                <div class="form-group">
                    <div class="d-flex justify-between">
                        <label for="password" class="form-label">Password</label>
                        <a href="{{ route('password.request') }}" style="font-size: var(--font-size-sm);">Forgot
                            password?</a>
                    </div>
                    <div style="position: relative;">
                        <input type="password" id="password" name="password" class="form-control"
                            placeholder="Enter your password" required>
                        <button type="button" id="togglePassword" class="i-btn"
                            style="position: absolute; right: 0; top: 0; height: 100%; color: var(--text-muted); border: none; background: transparent; margin-right: 15px; cursor: pointer;">
                            <i class="far fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="form-check">
                    <input type="checkbox" id="remember" class="form-check-input">
                    <label for="remember" class="form-check-label">Remember me on this device</label>
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

                <button type="submit" class="btn btn-primary btn-full mt-2">
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
