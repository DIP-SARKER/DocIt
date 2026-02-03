@extends('layouts.guest')

@section('title', 'Verify Email - DocIt')

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

                <h2 class="text-center">Verify your email address</h2>

                <p class="text-muted text-center">
                    We sent a verification link to:
                    <strong>{{ auth()->user()?->email }}</strong>
                </p>

                <p class="text-muted text-center" style="font-size: var(--font-size-sm);">
                    Please check your inbox (and spam/promotions). Click the link to activate your account.
                </p>
            </div>

            {{-- ✅ Breeze session status --}}
            @if (session('status') === 'verification-link-sent')
                <div class="alert alert-success d-flex align-center gap-2 mb-3" id="ve-statusBox" role="status">
                    <i class="fas fa-check-circle"></i>
                    <div>A new verification link has been sent to your email address.</div>
                </div>
            @endif

            {{-- ✅ Resend form --}}
            <form class="d-flex flex-column" id="ve-resendForm" method="POST" action="{{ route('verification.send') }}">
                @csrf

                <button type="submit" class="btn btn-outline mt-2 form-btn" id="ve-resendBtn">
                    <i class="fas fa-paper-plane"></i>
                    <span id="ve-resendText">Resend verification email</span>
                </button>

                <p class="text-muted mt-2 text-center" id="ve-rateInfo"
                    style="font-size: var(--font-size-sm); display:none;">
                    <i class="fas fa-shield-alt"></i>
                    <span id="ve-rateText"></span>
                </p>

                {{-- ✅ Error summary (rare here, but keep consistent) --}}
                @if ($errors->any())
                    <div id="ve-errorSummary" class="alert alert-danger align-center d-flex gap-2 mt-4" role="alert">
                        <i class="fas fa-exclamation-circle"></i>
                        <div>
                            <ul class="mb-0" style="padding-left: var(--space-sm)">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </form>

            {{-- ✅ Logout form --}}
            <form method="POST" action="{{ route('logout') }}" class="d-flex mt-3 justify-center">
                @csrf
                <button type="submit" class="btn btn-secondary w-100" id="ve-logoutBtn">
                    <i class="fas fa-sign-out-alt"></i>
                    Log out
                </button>
            </form>
        </div>
    </div>

    <script>
        (function() {
            // ✅ Set this to your server throttle window.
            // Breeze often throttles verification resends; if you want exact match, set this to route middleware value.
            const THROTTLE_SECONDS = 60; // conservative UX (prevents spam-clicks)
            const MAX_ATTEMPTS = 6; // optional UX limit per window (matches common throttle patterns)
            const WINDOW_MINUTES = 10; // optional window for attempt counting

            const STORAGE_KEY = "docit_ve_rate_v1";

            const form = document.getElementById("ve-resendForm");
            const resendBtn = document.getElementById("ve-resendBtn");
            const resendText = document.getElementById("ve-resendText");
            const rateInfo = document.getElementById("ve-rateInfo");
            const rateText = document.getElementById("ve-rateText");

            if (!form || !resendBtn || !resendText || !rateInfo || !rateText) return;

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

            function pruneAttempts(attempts) {
                const windowMs = WINDOW_MINUTES * 60 * 1000;
                const cutoff = nowMs() - windowMs;
                return attempts.filter(t => t >= cutoff);
            }

            function setDisabled(disabled) {
                resendBtn.disabled = disabled;
                resendBtn.style.opacity = disabled ? "0.7" : "1";
                resendBtn.style.cursor = disabled ? "not-allowed" : "pointer";
            }

            function showRateInfo(message) {
                rateInfo.style.display = "block";
                rateText.textContent = message;
            }

            function hideRateInfo() {
                rateInfo.style.display = "none";
                rateText.textContent = "";
            }

            let timer = null;

            function render() {
                const state = loadState();
                state.attempts = pruneAttempts(state.attempts);
                saveState(state);

                // Optional attempt window lock
                if (state.attempts.length >= MAX_ATTEMPTS) {
                    setDisabled(true);
                    resendText.textContent = "Try again later";
                    showRateInfo("Too many resend attempts. Please wait and try again later.");
                    return;
                }

                // Cooldown lock
                const remainingMs = state.cooldownUntil - nowMs();
                if (remainingMs > 0) {
                    const s = Math.ceil(remainingMs / 1000);
                    setDisabled(true);
                    resendText.textContent = `Please wait (${s}s)`;
                    showRateInfo(`To avoid spam, you can resend again in ${s} seconds.`);
                    return;
                }

                setDisabled(false);
                resendText.textContent = "Resend verification email";
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

                // Apply cooldown immediately (UX)
                state.attempts.push(nowMs());
                state.cooldownUntil = nowMs() + (THROTTLE_SECONDS * 1000);
                saveState(state);

                setDisabled(true);
                resendText.textContent = "Sending...";
                showRateInfo(`Request sent. You can resend again in ${THROTTLE_SECONDS} seconds.`);
            });
        })();
    </script>
@endsection
