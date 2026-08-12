(function () {
    const toggle = document.getElementById("password-toggle");
    const input = document.getElementById("password");
    if (!toggle || !input) return;

    toggle.addEventListener("click", function () {
        const isVisible = input.type === "text";
        input.type = isVisible ? "password" : "text";
        toggle.setAttribute("aria-pressed", isVisible ? "false" : "true");
        toggle.setAttribute(
            "aria-label",
            isVisible ? "Tampilkan password" : "Sembunyikan password",
        );
    });
})();
