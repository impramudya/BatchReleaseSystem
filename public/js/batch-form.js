(function () {
    const productSelect = document.getElementById("product_id");
    const productNameDisplay = document.getElementById("product_name_display");

    if (productSelect && productNameDisplay) {
        function syncProductName() {
            const selected = productSelect.options[productSelect.selectedIndex];
            productNameDisplay.value = selected
                ? selected.getAttribute("data-name") || ""
                : "";
        }
        productSelect.addEventListener("change", syncProductName);
        syncProductName();
    }

    const groups = document.querySelectorAll("[data-answer-group]");
    const progressFill = document.getElementById("progress-fill");
    const progressCount = document.getElementById("progress-count");

    function updateProgress() {
        if (!groups.length || !progressFill || !progressCount) return;

        let answered = 0;
        groups.forEach(function (group) {
            if (group.querySelector('input[type="radio"]:checked')) {
                answered++;
            }
        });

        const total = groups.length;
        const percent = total > 0 ? Math.round((answered / total) * 100) : 0;

        progressFill.style.width = percent + "%";
        progressCount.textContent = answered + "/" + total;
    }

    groups.forEach(function (group) {
        group.addEventListener("change", updateProgress);
    });

    updateProgress();
})();
