/**
 * BatchReleasePro - Enterprise Login Interactions
 * Quality Assurance & Batch Release System
 */
(function () {
    'use strict';

    // Password Visibility Toggle
    const toggle = document.getElementById("password-toggle");
    const passwordInput = document.getElementById("password");

    if (toggle && passwordInput) {
        toggle.addEventListener("click", function () {
            const isVisible = passwordInput.type === "text";
            passwordInput.type = isVisible ? "password" : "text";
            toggle.setAttribute("aria-pressed", isVisible ? "false" : "true");
            toggle.setAttribute(
                "aria-label",
                isVisible ? "Tampilkan kata sandi" : "Sembunyikan kata sandi"
            );
            passwordInput.focus();
        });
    }

    // Form Submit Feedback State
    const form = document.querySelector(".brp-form");
    const submitBtn = document.querySelector(".brp-btn-submit");

    if (form && submitBtn) {
        form.addEventListener("submit", function () {
            // Check HTML5 validity
            if (form.checkValidity()) {
                submitBtn.disabled = true;
                submitBtn.style.opacity = "0.85";
                submitBtn.style.cursor = "wait";
                const originalHtml = submitBtn.innerHTML;
                submitBtn.innerHTML = `
                    <svg style="animation: brp-spin 1s linear infinite; width: 18px; height: 18px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-opacity="0.25"></circle>
                        <path d="M12 2a10 10 0 0 1 10 10" stroke="currentColor"></path>
                    </svg>
                    <span>Memverifikasi Kredensial...</span>
                `;
            }
        });
    }
})();
