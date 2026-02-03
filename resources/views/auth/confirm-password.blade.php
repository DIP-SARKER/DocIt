@extends('layouts.app')

@section('title', 'Confirm Password - DocIt')

@section('content')

    <main class="main-content" style="min-height: 70vh;">
        <div class="container" style="max-width: 600px;">
            <div class="card">
                <div class="mb-3 d-flex flex-column align-center">
                    <a href="{{ route('home') }}" class="navbar-brand not-hover">
                        <i class="fas fa-file-alt"></i>
                        DocIt
                    </a>
                    <h2 class="text-center">Confirm your password</h2>
                    <p class="text-muted text-center">
                        This is a secure area. Please confirm your password to continue.
                    </p>
                </div>

                <form class="d-flex flex-column" id="cp-form" method="POST" action="{{ route('password.confirm') }}">
                    @csrf

                    <div class="form-group mb-0">
                        <label for="cp-password" class="form-label">Password</label>

                        <div style="position: relative;">
                            {{-- ✅ IMPORTANT: Breeze expects name="password" --}}
                            <input type="password" name="password" id="cp-password" class="form-control"
                                placeholder="Enter your current password" autocomplete="current-password" required autofocus
                                aria-describedby="cp-passHint cp-errorSummary">

                            <button type="button" id="cp-togglePassword" class="i-btn"
                                style="position:absolute; right:0; top:0; height:100%; color:var(--text-muted); border:none; background:transparent; margin-right:15px; cursor:pointer;"
                                aria-label="Show password" aria-pressed="false">
                                <i class="far fa-eye"></i>
                            </button>
                        </div>

                        <p class="text-muted" id="cp-passHint"
                            style="font-size: var(--font-size-sm); margin-top: var(--space-xs);">
                            <i class="fas fa-info-circle"></i>
                            Enter the password for your account.
                        </p>
                    </div>

                    @if ($errors->any())
                        <div id="cp-errorSummary" class="alert alert-danger align-center d-flex gap-2 mt-4" role="alert">
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
                        <div id="cp-errorSummary" class="hidden"></div>
                    @endif

                    <button type="submit" id="cp-submitBtn" class="btn btn-outline mt-2 form-btn">
                        <i class="fas fa-check"></i>
                        Confirm
                    </button>

                    {{-- Optional: link to forgot password --}}
                    <a href="{{ route('password.request') }}" class="text-muted mt-3"
                        style="font-size: var(--font-size-sm); text-align:center;">
                        Forgot your password?
                    </a>
                </form>
            </div>
        </div>
    </main>

    <script>
        (function() {
            const password = document.getElementById("cp-password");
            const toggleBtn = document.getElementById("cp-togglePassword");
            const hint = document.getElementById("cp-passHint");
            const submitBtn = document.getElementById("cp-submitBtn");
            const form = document.getElementById("cp-form");

            if (!password || !toggleBtn || !hint || !submitBtn || !form) return;

            function setHint(el, message, color = "var(--text-muted)") {
                el.innerHTML = `<i class="fas fa-info-circle"></i> ${message}`;
                el.style.color = color;
            }

            // Toggle show/hide password
            toggleBtn.addEventListener("click", () => {
                const isHidden = password.type === "password";
                password.type = isHidden ? "text" : "password";

                toggleBtn.setAttribute("aria-pressed", isHidden ? "true" : "false");
                toggleBtn.setAttribute("aria-label", isHidden ? "Hide password" : "Show password");

                const icon = toggleBtn.querySelector("i");
                if (icon) {
                    icon.classList.toggle("fa-eye", !isHidden);
                    icon.classList.toggle("fa-eye-slash", isHidden);
                }
            });

            // Friendly input feedback (not strength — just “entered / empty”)
            password.addEventListener("input", () => {
                const v = password.value;

                if (!v) {
                    setHint(hint, "Enter the password for your account.");
                } else if (v.length < 8) {
                    setHint(hint, "Password seems too short. Check again.", "var(--warning)");
                } else {
                    setHint(hint, "Looks good.", "var(--accent)");
                }
            });

            // Prevent double submit spam (UX)
            form.addEventListener("submit", () => {
                submitBtn.disabled = true;
                submitBtn.style.opacity = "0.7";
                submitBtn.style.cursor = "not-allowed";
            });
        })();
    </script>
@endsection
