@extends('layouts.guest')

@section('title', 'Reset Password - DocIt')

@section('content')
    <div class="container" style="max-width: 600px;">
        <!-- Back to Home -->
        <a href="{{ route('home') }}" class="d-flex align-center gap-1 mb-3" style="color: var(--text-muted);">
            <i class="fas fa-arrow-left"></i>
            Back to home
        </a>

        <div class="card">
            <div class="mb-3 d-flex flex-column align-center">
                <a href="{{ route('home') }}" class="navbar-brand not-hover">
                    <i class="fas fa-file-alt"></i>
                    DocIt
                </a>
                <h2 class="text-center">Set up a new password</h2>
                <p class="text-muted text-center" style="font-size: var(--font-size-sm);">
                    <i class="fas fa-info-circle"></i>
                    This link expires after <strong>60 minutes</strong>.
                </p>
            </div>

            <form class="d-flex flex-column" id="rp-form" method="POST" action="{{ route('password.store') }}">
                @csrf

                {{-- ✅ Required by Breeze --}}
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                {{-- ✅ Email is required by default Breeze reset flow --}}
                <div class="form-group mb-0">
                    <label for="rp-email" class="form-label">Email Address</label>
                    <input type="email" id="rp-email" name="email" class="form-control" placeholder="you@example.com"
                        autocomplete="username" value="{{ old('email', $request->email) }}" required autofocus
                        aria-describedby="rp-emailHint rp-errorSummary">
                    <p class="text-muted" id="rp-emailHint" style="font-size: var(--font-size-sm);">
                        <i class="fas fa-info-circle"></i>
                        Use the same email you requested the reset for.
                    </p>
                </div>

                <div class="form-group mb-0">
                    <label for="rp-password" class="form-label">New Password</label>
                    <div style="position: relative;">
                        <input type="password" id="rp-password" name="password" class="form-control"
                            placeholder="Create a strong password" autocomplete="new-password" required
                            aria-describedby="rp-passHint">
                        <button type="button" id="rp-togglePassword" class="i-btn"
                            style="position:absolute; right:0; top:0; height:100%; color:var(--text-muted); border:none; background:transparent; margin-right:15px; cursor:pointer;"
                            aria-label="Show password" aria-pressed="false">
                            <i class="far fa-eye"></i>
                        </button>
                    </div>

                    <p class="text-muted" id="rp-passHint"
                        style="font-size: var(--font-size-sm); margin-top: var(--space-xs);">
                        <i class="fas fa-info-circle"></i>
                        Min. 8 chars, include upper/lowercase, numbers, symbols.
                    </p>
                </div>

                <div class="form-group mb-0">
                    <label for="rp-confirmPassword" class="form-label">Confirm Password</label>
                    <div style="position: relative;">
                        <input type="password" name="password_confirmation" id="rp-confirmPassword" class="form-control"
                            placeholder="Confirm your password" autocomplete="new-password" required
                            aria-describedby="rp-cpHint">
                        <button type="button" id="rp-toggleCP" class="i-btn"
                            style="position:absolute; right:0; top:0; height:100%; color:var(--text-muted); border:none; background:transparent; margin-right:15px; cursor:pointer;"
                            aria-label="Show confirm password" aria-pressed="false">
                            <i class="far fa-eye"></i>
                        </button>
                    </div>

                    <p class="text-muted" id="rp-cpHint"
                        style="font-size: var(--font-size-sm); margin-top: var(--space-xs);">
                        <i class="fas fa-info-circle"></i>
                        Ensure this matches your chosen password.
                    </p>
                </div>

                {{-- ✅ Error Summary --}}
                @if ($errors->any())
                    <div id="rp-errorSummary" class="alert alert-danger align-center d-flex gap-2 mt-4" role="alert">
                        <i class="fas fa-exclamation-circle"></i>
                        <div>
                            <ul class="mb-0" style="padding-left: var(--space-sm)">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @else
                    <div id="rp-errorSummary" class="hidden"></div>
                @endif
                <div class="d-flex justify-center">
                    <div class="cf-turnstile" data-sitekey="{{ config('services.turnstile.sitekey') }}">
                    </div>
                </div>

                <button type="submit" id="rp-submitBtn" class="btn btn-outline mt-2 form-btn">
                    <i class="fas fa-wrench"></i>
                    Update Password
                </button>
            </form>
        </div>
    </div>

    <script>
        (function() {
            // Small helper like your $("id") style
            const $ = (id) => document.getElementById(id);

            function setHint(el, message, color = "var(--text-muted)") {
                if (!el) return;
                el.innerHTML = `<i class="fas fa-info-circle"></i> ${message}`;
                el.style.color = color;
            }

            /* ===============================
               Toggle Visibility
            =============================== */
            function bindToggle(inputId, btnId) {
                const input = $(inputId);
                const btn = $(btnId);
                if (!input || !btn) return;

                btn.addEventListener("click", () => {
                    const isHidden = input.type === "password";
                    input.type = isHidden ? "text" : "password";
                    btn.setAttribute("aria-pressed", isHidden ? "true" : "false");
                    btn.setAttribute("aria-label", isHidden ? "Hide password" : "Show password");

                    const icon = btn.querySelector("i");
                    if (icon) {
                        icon.classList.toggle("fa-eye", !isHidden);
                        icon.classList.toggle("fa-eye-slash", isHidden);
                    }
                });
            }

            bindToggle("rp-password", "rp-togglePassword");
            bindToggle("rp-confirmPassword", "rp-toggleCP");

            /* ===============================
               Password Strength Hint
            =============================== */
            const password = $("rp-password");
            const pwHint = $("rp-passHint");

            if (password && pwHint) {
                password.addEventListener("input", () => {
                    const v = password.value;

                    if (!v) {
                        setHint(
                            pwHint,
                            "Min. 8 chars, include upper/lowercase, numbers, symbols."
                        );
                        return;
                    }

                    if (v.length < 8) {
                        setHint(
                            pwHint,
                            "Password too short (min 8 characters).",
                            "var(--danger)"
                        );
                        return;
                    }

                    const strength =
                        (/[a-z]/.test(v) ? 1 : 0) +
                        (/[A-Z]/.test(v) ? 1 : 0) +
                        (/[0-9]/.test(v) ? 1 : 0) +
                        (/[^a-zA-Z0-9]/.test(v) ? 1 : 0);

                    if (strength < 3) {
                        setHint(
                            pwHint,
                            "Weak password. Add numbers or symbols.",
                            "var(--warning)"
                        );
                    } else {
                        setHint(pwHint, "Strong password.", "var(--accent)");
                    }
                });
            }

            /* ===============================
               Confirm Password Hint
            =============================== */
            const confirmPassword = $("rp-confirmPassword");
            const cpHint = $("rp-cpHint");

            function updateConfirmHint() {
                if (!confirmPassword || !password || !cpHint) return;

                if (!confirmPassword.value) {
                    setHint(cpHint, "Ensure this matches your chosen password.");
                    return;
                }

                if (confirmPassword.value === password.value) {
                    setHint(cpHint, "Passwords match.", "var(--accent)");
                } else {
                    setHint(cpHint, "Passwords do not match.", "var(--danger)");
                }
            }

            if (confirmPassword && password && cpHint) {
                confirmPassword.addEventListener("input", updateConfirmHint);
                password.addEventListener("input", updateConfirmHint); // if password changes after confirm
            }

            /* ===============================
               Optional: Email format hint (same as forgot page style)
            =============================== */
            const email = $("rp-email");
            const hintEmail = $("rp-emailHint");

            if (email && hintEmail) {
                email.addEventListener("input", () => {
                    const value = email.value.trim();

                    if (!value) {
                        setHint(hintEmail, "Use the same email you requested the reset for.");
                        return;
                    }

                    const isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);

                    if (!isValid) {
                        setHint(hintEmail, "Please enter a valid email (e.g. you@example.com).",
                            "var(--danger)");
                    } else {
                        setHint(hintEmail, "Email format looks valid.", "var(--accent)");
                    }
                });
            }
        })();
    </script>
@endsection
