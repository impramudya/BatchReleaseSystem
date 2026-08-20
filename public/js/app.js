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

(function () {
    const sidebar = document.getElementById("brp-sidebar");
    const hamburger = document.getElementById("sidebar-toggle");
    const overlay = document.getElementById("sidebar-overlay");
    if (!sidebar || !hamburger || !overlay) return;

    function openSidebar() {
        sidebar.classList.add("is-open");
        overlay.classList.add("is-open");
        hamburger.setAttribute("aria-expanded", "true");
    }

    function closeSidebar() {
        sidebar.classList.remove("is-open");
        overlay.classList.remove("is-open");
        hamburger.setAttribute("aria-expanded", "false");
    }

    hamburger.addEventListener("click", function (e) {
        e.stopPropagation();
        if (sidebar.classList.contains("is-open")) {
            closeSidebar();
        } else {
            openSidebar();
        }
    });

    overlay.addEventListener("click", closeSidebar);

    document.addEventListener("keydown", function (e) {
        if (e.key === "Escape") closeSidebar();
    });

    window.addEventListener("resize", function () {
        if (window.innerWidth > 900) closeSidebar();
    });
})();
