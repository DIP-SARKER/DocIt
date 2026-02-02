/**
 * DocIt - Shared JavaScript Functions
 * Version: 1.0
 * Description: Handles UI interactions and common functionality
 */

document.addEventListener("DOMContentLoaded", function () {
    // ============================================
    // Global Functions
    // ============================================

    // ============================================
    //my codes

    const navbar = document.querySelector(".navbar");
    const navbarContainer = document.querySelector(".navbar-container");

    window.addEventListener("scroll", function () {
        if (window.scrollY > 50) {
            navbar.classList.add("navbar-scrolled");
            navbarContainer.classList.add("navbar-container-scrolled");
        } else {
            navbar.classList.remove("navbar-scrolled");
            navbarContainer.classList.remove("navbar-container-scrolled");
        }
    });

    const termsTicker = document.getElementById("terms");
    const registerSubmitBtn = document.getElementById("registerSubmitBtn");
    if (termsTicker && registerSubmitBtn) {
        termsTicker.addEventListener("change", function () {
            registerSubmitBtn.disabled = !this.checked;
        });
    }

    // Toggle Password Visibility
    function attachToggle(btn) {
        btn.addEventListener("click", function () {
            const input = this.previousElementSibling;
            const icon = this.querySelector("i");

            if (!input) return;

            const show = input.type === "password";
            input.type = show ? "text" : "password";

            icon.classList.toggle("fa-eye", !show);
            icon.classList.toggle("fa-eye-slash", show);
        });
    }

    const togglePasswordButton = document.getElementById("togglePassword");
    const toggleCPasswordBtn = document.getElementById("toggleCP");

    if (togglePasswordButton) attachToggle(togglePasswordButton);
    if (toggleCPasswordBtn) attachToggle(toggleCPasswordBtn);

    // ===============Ux Refinement================
    /* ===============================
     Helpers
     =============================== */

    const $ = (id) => document.getElementById(id);

    const setHint = (el, text, color = "var(--text-muted)") => {
        if (!el) return;
        el.innerHTML = `<i class="fas fa-info-circle"></i> ${text}`;
        el.style.color = color;
    };

    /* ===============================
     Username Hint
     =============================== */

    const nameInput = $("fullName");
    const nameHint = $("hintName");

    if (nameInput && nameHint) {
        nameInput.addEventListener("input", function () {
            // Restrict characters
            this.value = this.value.replace(/[^a-zA-Z0-9_-]/g, "");

            // Enforce max length
            if (this.value.length > 50) {
                this.value = this.value.slice(0, 50);
            }

            // Hint logic
            if (this.value.length > 0 && this.value.length < 6) {
                setHint(
                    nameHint,
                    "Minimum 6 characters required.",
                    "var(--danger)",
                );
            } else {
                setHint(nameHint, "Select a unique username.");
            }
        });
    }

    /* ===============================
     Email Hint
     =============================== */

    const email = $("email");
    const hintEmail = $("hintEmail");

    if (email && hintEmail) {
        email.addEventListener("input", () => {
            const value = email.value.trim();

            if (!value) {
                setHint(
                    hintEmail,
                    "Email must be unique and not already registered.",
                );
                return;
            }

            const isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);

            if (!isValid) {
                setHint(
                    hintEmail,
                    "Please enter a valid email (e.g. you@example.com).",
                    "var(--danger)",
                );
            } else {
                setHint(hintEmail, "Email looks valid.", "var(--accent)");
            }
        });
    }

    /* ===============================
     Password Hint
     =============================== */

    const password = $("password");
    const pwHint = $("hintPass");

    if (password && pwHint) {
        password.addEventListener("input", () => {
            const v = password.value;

            if (!v) {
                setHint(
                    pwHint,
                    "Min. 8 chars, include upper/lowercase, numbers, symbols.",
                );
                return;
            }

            if (v.length < 8) {
                setHint(
                    pwHint,
                    "Password too short (min 8 characters).",
                    "var(--danger)",
                );
                return;
            }

            const strength =
                /[a-z]/.test(v) +
                /[A-Z]/.test(v) +
                /[0-9]/.test(v) +
                /[^a-zA-Z0-9]/.test(v);

            if (strength < 3) {
                setHint(
                    pwHint,
                    "Weak password. Add numbers or symbols.",
                    "var(--warning)",
                );
            } else {
                setHint(pwHint, "Strong password.", "var(--accent)");
            }
        });
    }

    /* ===============================
     Confirm Password Hint
     =============================== */

    const confirmPassword = $("confirmPassword");
    const cpHint = $("hintCP");

    if (confirmPassword && password && cpHint) {
        confirmPassword.addEventListener("input", () => {
            if (!confirmPassword.value) {
                setHint(cpHint, "Ensure this matches your chosen password.");
                return;
            }

            if (confirmPassword.value === password.value) {
                setHint(cpHint, "Passwords match.", "var(--accent)");
            } else {
                setHint(cpHint, "Passwords do not match.", "var(--danger)");
            }
        });
    }
});
