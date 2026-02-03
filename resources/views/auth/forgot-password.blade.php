@extends('layouts.guest')

@section('title', 'Forgot Password - DocIt')

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
                <h2 class="text-center">Forgot your password?</h2>
                <p class="text-muted text-center">Enter your email and we’ll send you a reset link.</p>

                {{-- ✅ Config-based guidance (matches config/auth.php) --}}
                <p class="text-muted text-center" style="font-size: var(--font-size-sm); margin-top: var(--space-xs);">
                    <i class="fas fa-info-circle"></i>
                    The reset link expires in <strong>60 minutes</strong>. You can request a new link about once every
                    <strong>60 seconds</strong>.
                </p>
            </div>

            {{-- ✅ Success status from Laravel --}}
            @if (session('status'))
                <div class="alert alert-success d-flex align-center gap-2 mb-3" id="fp-statusBox" role="status">
                    <i class="fas fa-check-circle"></i>
                    <div>{{ session('status') }}</div>
                </div>
            @endif

            <form class="d-flex flex-column" id="fp-form" method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="form-group mb-0">
                    <label for="fp-email" class="form-label">Email Address</label>
                    <input type="email" id="fp-email" name="email" class="form-control" placeholder="you@example.com"
                        autocomplete="email" value="{{ old('email') }}" required autofocus
                        aria-describedby="fp-emailHint fp-errorSummary">

                    <p class="text-muted" id="fp-emailHint" style="font-size: var(--font-size-sm);">
                        <i class="fas fa-info-circle"></i>
                        Use the email you signed up with.
                    </p>
                </div>

                {{-- ✅ Error Message --}}
                @if ($errors->any())
                    <div id="fp-errorSummary" class="alert alert-danger align-center d-flex gap-2 mt-4" role="alert">
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
                    <div id="fp-errorSummary" class="hidden"></div>
                @endif

                <button type="submit" class="btn btn-outline mt-2 form-btn" id="fp-submitBtn">
                    <i class="fas fa-paper-plane"></i>
                    <span id="fp-submitText">Send reset link</span>
                </button>

                {{-- ✅ Helper text for cooldown / attempts --}}
                <p class="text-muted mt-2 text-center" id="fp-rateInfo" style="font-size: var(--font-size-sm); display:none;">
                    <i class="fas fa-shield-alt"></i>
                    <span id="fp-rateText"></span>
                </p>
            </form>
        </div>
    </div>

    <script>
        (function() {
            const emailInput = document.getElementById("fp-email");
            const emailHint = document.getElementById("fp-emailHint");

            if (!emailInput || !emailHint) return;

            function setHint(el, message, color = "var(--text-muted)") {
                el.innerHTML = `<i class="fas fa-info-circle"></i> ${message}`;
                el.style.color = color;
            }

            emailInput.addEventListener("input", function() {
                const value = emailInput.value.trim();

                // Empty state
                if (!value) {
                    setHint(
                        emailHint,
                        "Enter the email you used to register."
                    );
                    return;
                }

                // Email format validation
                const isValidEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);

                if (!isValidEmail) {
                    setHint(
                        emailHint,
                        "Please enter a valid email (e.g. you@example.com).",
                        "var(--danger)"
                    );
                } else {
                    setHint(
                        emailHint,
                        "Email format looks valid.",
                        "var(--accent)"
                    );
                }
            });
            // These match your config/auth.php:
            const THROTTLE_SECONDS = 60; // 'throttle' => 60
            const EXPIRE_MINUTES = 60; // 'expire' => 60 (informational only)

            // UX rate limit (client-side, not security):
            // max 3 submissions per 15 minutes in this browser.
            const MAX_ATTEMPTS = 4;
            const WINDOW_MINUTES = 15;

            const STORAGE_KEY = "docit_fp_rate_v2";

            const form = document.getElementById("fp-form");
            const submitBtn = document.getElementById("fp-submitBtn");
            const submitText = document.getElementById("fp-submitText");
            const rateInfo = document.getElementById("fp-rateInfo");
            const rateText = document.getElementById("fp-rateText");

            if (!form || !submitBtn || !submitText || !rateInfo || !rateText) return;

            function nowMs() {
                return Date.now();
            }

            function loadState() {
                try {
                    const raw = localStorage.getItem(STORAGE_KEY);
                    const parsed = raw ? JSON.parse(raw) : null;
                    if (!parsed || typeof parsed !== "object") {
                        return {
                            attempts: [],
                            cooldownUntil: 0
                        };
                    }
                    return {
                        attempts: Array.isArray(parsed.attempts) ? parsed.attempts.map(Number) : [],
                        cooldownUntil: Number(parsed.cooldownUntil || 0),
                    };
                } catch {
                    return {
                        attempts: [],
                        cooldownUntil: 0
                    };
                }
            }

            function saveState(state) {
                localStorage.setItem(STORAGE_KEY, JSON.stringify(state));
            }

            function setDisabled(disabled) {
                submitBtn.disabled = disabled;
                submitBtn.style.opacity = disabled ? "0.7" : "1";
                submitBtn.style.cursor = disabled ? "not-allowed" : "pointer";
            }

            function showRateInfo(message) {
                rateInfo.style.display = "block";
                rateText.textContent = message;
            }

            function hideRateInfo() {
                rateInfo.style.display = "none";
                rateText.textContent = "";
            }

            function pruneAttempts(attempts) {
                const windowMs = WINDOW_MINUTES * 60 * 1000;
                const cutoff = nowMs() - windowMs;
                return attempts.filter(t => t >= cutoff);
            }

            let timer = null;

            function render() {
                const state = loadState();
                state.attempts = pruneAttempts(state.attempts);
                saveState(state);

                // Attempts window
                if (state.attempts.length >= MAX_ATTEMPTS) {
                    setDisabled(true);
                    submitText.textContent = "Try again later";
                    showRateInfo(`You’ve requested too many times. Please wait a bit and try again.`);
                    return;
                }

                // Cooldown (matches server throttle)
                const remainingMs = state.cooldownUntil - nowMs();
                if (remainingMs > 0) {
                    const s = Math.ceil(remainingMs / 1000);
                    setDisabled(true);
                    submitText.textContent = `Please wait (${s}s)`;
                    showRateInfo(`You can request another email in ${s} seconds.`);
                    return;
                }

                setDisabled(false);
                submitText.textContent = "Send reset link";
                hideRateInfo();
            }

            function startTicker() {
                if (timer) clearInterval(timer);
                timer = setInterval(render, 500);
            }

            render();
            startTicker();

            form.addEventListener("submit", function(event) {
                const state = loadState();
                state.attempts = pruneAttempts(state.attempts);

                // Guard if blocked
                if (state.attempts.length >= MAX_ATTEMPTS) {
                    event.preventDefault();
                    render();
                    return;
                }
                if (state.cooldownUntil > nowMs()) {
                    event.preventDefault();
                    render();
                    return;
                }

                // Record attempt + set cooldown aligned to throttle
                state.attempts.push(nowMs());
                state.cooldownUntil = nowMs() + (THROTTLE_SECONDS * 1000);
                saveState(state);

                // UI feedback
                setDisabled(true);
                submitText.textContent = "Sending...";
                showRateInfo(
                    `If the email exists, a reset link will be sent. You can request again in ${THROTTLE_SECONDS} seconds.`
                );
            });
        })();
    </script>
@endsection
