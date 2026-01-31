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

    // ============================================
});
