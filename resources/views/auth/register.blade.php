@extends('layouts.guest')

@section('title', 'Create Account - DocIt')

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
                <h2 class="text-center">Create your account</h2>
                <p class="text-muted text-center">Get started with DocIt in less than a minute</p>
            </div>

            <!-- Register Form -->
            <form class="d-flex flex-column" id="registerForm" method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-group mb-0">
                    <label for="fullName" class="form-label">UserName</label>
                    <input type="text" name="name" id="fullName" class="form-control"
                        placeholder="lionel_andrés_messi" required>
                    <p class="text-muted" id="hintName"
                        style="font-size: var(--font-size-sm); margin-top: var(--space-xs);">
                        <i class="fas fa-info-circle"></i>
                        Select a unique username.
                    </p>
                </div>

                <div class="form-group mb-0">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="you@example.com"
                        autocomplete="email" required>
                    <p class="text-muted" id="hintEmail"
                        style="font-size: var(--font-size-sm); margin-top: var(--space-xs);">
                        <i class="fas fa-info-circle"></i>
                        Email must be unique and not already registered.
                    </p>
                </div>

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

                <div class="form-check mb-0">
                    <!-- ✅ Added name -->
                    <input type="checkbox" name="terms" id="terms" class="form-check-input" required>
                    <label for="terms" class="form-check-label">
                        I agree to the <a href="{{ route('terms-and-conditions') }}">Terms of Service</a> and <a
                            href="{{ route('privacy-policy') }}">Privacy Policy</a>
                    </label>
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
                <button type="submit" id="registerSubmitBtn" class="btn btn-outline mt-2 form-btn" disabled>
                    <i class="fas fa-user-plus"></i>
                    Create Account
                </button>
            </form>


            <div class="text-center mt-3">
                <p class="text-muted">Already have an account? <a href="{{ route('login') }}">Sign in here</a></p>
            </div>

            <div class="text-center mt-3">
                <p class="text-muted" style="font-size: var(--font-size-sm);">
                    <i class="fas fa-shield-alt"></i>
                    Your data is encrypted and we never store plain text passwords.
                </p>
            </div>
        </div>
    </div>
@endsection
