(function () {
    const wrap = document.getElementById("user-menu-wrap");
    const trigger = document.getElementById("user-menu-trigger");
    if (!wrap || !trigger) return;

    trigger.addEventListener("click", function (e) {
        e.stopPropagation();
        const isOpen = wrap.classList.toggle("is-open");
        trigger.setAttribute("aria-expanded", isOpen ? "true" : "false");
    });

    document.addEventListener("click", function (e) {
        if (!wrap.contains(e.target)) {
            wrap.classList.remove("is-open");
            trigger.setAttribute("aria-expanded", "false");
        }
    });

    document.addEventListener("keydown", function (e) {
        if (e.key === "Escape") {
            wrap.classList.remove("is-open");
            trigger.setAttribute("aria-expanded", "false");
        }
    });
})();
