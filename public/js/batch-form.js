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

    // Toggle sub-validasi Point G
    const radioValidasiYa = document.getElementById("batch_validasi_ya");
    const radioValidasiTidak = document.getElementById("batch_validasi_tidak");
    const subValidasiContainer = document.getElementById("sub-validasi-container");
    const radioLaporanTidak = document.getElementById("laporan_validasi_tidak");
    const radioLaporanAda = document.getElementById("laporan_validasi_ada");
    const inputNomorLaporan = document.getElementById("nomor_laporan_validasi");

    function toggleSubValidasi() {
        if (!subValidasiContainer) return;
        if (radioValidasiYa && radioValidasiYa.checked) {
            subValidasiContainer.style.display = "block";
            if (inputNomorLaporan && inputNomorLaporan.value === "N/A") {
                inputNomorLaporan.value = "";
            }
        } else {
            subValidasiContainer.style.display = "none";
            if (radioValidasiTidak && radioValidasiTidak.checked) {
                if (radioLaporanTidak) radioLaporanTidak.checked = true;
                if (inputNomorLaporan) inputNomorLaporan.value = "N/A";
            }
        }
    }

    if (radioValidasiYa && radioValidasiTidak) {
        radioValidasiYa.addEventListener("change", toggleSubValidasi);
        radioValidasiTidak.addEventListener("change", toggleSubValidasi);
        toggleSubValidasi();
    }
})();
