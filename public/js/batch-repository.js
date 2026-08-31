(function () {
    const tabButtons = document.querySelectorAll("[data-tab-btn]");
    const tabPanels = document.querySelectorAll("[data-tab-panel]");

    if (!tabButtons.length) return;

    tabButtons.forEach(function (btn) {
        btn.addEventListener("click", function () {
            const target = btn.getAttribute("data-tab-btn");

            tabButtons.forEach(function (b) {
                b.classList.toggle("active", b === btn);
            });

            tabPanels.forEach(function (panel) {
                panel.classList.toggle(
                    "active",
                    panel.getAttribute("data-tab-panel") === target,
                );
            });
        });
    });
})();
