(function () {
    const backdrop = document.getElementById("tap-modal-backdrop");
    const form = document.getElementById("tap-modal-form");
    const title = document.getElementById("tap-modal-title");
    const batchLabel = document.getElementById("tap-modal-batch");
    const noteLabel = document.getElementById("tap-modal-note-label");
    const noteInput = document.getElementById("tap-modal-note");
    const submitBtn = document.getElementById("tap-modal-submit");
    const cancelBtn = document.getElementById("tap-modal-cancel");
    const errorEl = document.getElementById("tap-modal-error");

    const spvFields = document.getElementById("tap-spv-fields");
    const radioPenyimpanganTidak = document.getElementById("radio-penyimpangan-tidak");
    const radioPenyimpanganAda = document.getElementById("radio-penyimpangan-ada");
    const wrapPenyimpanganTable = document.getElementById("wrap-penyimpangan-table");
    const wrapPenyimpanganNa = document.getElementById("wrap-penyimpangan-na");
    const tbodyPenyimpangan = document.getElementById("tbody-penyimpangan");
    const btnAddPenyimpangan = document.getElementById("btn-add-penyimpangan");

    const radioCcTidak = document.getElementById("radio-cc-tidak");
    const radioCcAda = document.getElementById("radio-cc-ada");
    const wrapCcTable = document.getElementById("wrap-cc-table");
    const wrapCcNa = document.getElementById("wrap-cc-na");
    const tbodyCc = document.getElementById("tbody-cc");
    const btnAddCc = document.getElementById("btn-add-cc");

    if (!backdrop || !form) return;

    let currentAction = "approve";

    function resetSpvFields() {
        if (!spvFields) return;
        if (radioPenyimpanganTidak) radioPenyimpanganTidak.checked = true;
        if (wrapPenyimpanganTable) wrapPenyimpanganTable.style.display = "none";
        if (wrapPenyimpanganNa) wrapPenyimpanganNa.style.display = "block";
        if (tbodyPenyimpangan) {
            tbodyPenyimpangan.innerHTML = `
                <tr>
                    <td><input type="text" name="penyimpangan_nomor[]" placeholder="E.g., DEV/2026/001" class="tap-input-sm"></td>
                    <td><input type="text" name="penyimpangan_status[]" placeholder="E.g., Closed" class="tap-input-sm"></td>
                    <td style="text-align: center;"><button type="button" class="tap-btn-remove-row" title="Remove row">&times;</button></td>
                </tr>
            `;
        }

        if (radioCcTidak) radioCcTidak.checked = true;
        if (wrapCcTable) wrapCcTable.style.display = "none";
        if (wrapCcNa) wrapCcNa.style.display = "block";
        if (tbodyCc) {
            tbodyCc.innerHTML = `
                <tr>
                    <td><input type="text" name="change_control_nomor[]" placeholder="E.g., FUP/2026/001" class="tap-input-sm"></td>
                    <td><input type="text" name="change_control_status[]" placeholder="E.g., Closed" class="tap-input-sm"></td>
                    <td style="text-align: center;"><button type="button" class="tap-btn-remove-row" title="Remove row">&times;</button></td>
                </tr>
            `;
        }
    }

    function openModal(action, url, batchNumber, step) {
        currentAction = action;
        form.setAttribute("action", url);
        noteInput.value = "";
        errorEl.classList.remove("show");
        batchLabel.textContent = "Batch: " + batchNumber;

        if (action === "approve") {
            title.textContent = "Approve Batch";
            noteLabel.textContent = "Remarks (optional)";
            noteInput.required = false;
            submitBtn.textContent = "Approve";
            submitBtn.className = "brp-btn-primary";

            if (step === "supervisor" && spvFields) {
                resetSpvFields();
                spvFields.style.display = "block";
            } else if (spvFields) {
                spvFields.style.display = "none";
            }
        } else {
            title.textContent = "Reject Batch";
            noteLabel.textContent = "Rejection Reason (required)";
            noteInput.required = true;
            submitBtn.textContent = "Reject";
            submitBtn.className = "tap-btn-reject";

            if (spvFields) {
                spvFields.style.display = "none";
            }
        }

        backdrop.classList.add("open");
    }

    function closeModal() {
        backdrop.classList.remove("open");
    }

    // Toggle Laporan Penyimpangan
    if (radioPenyimpanganAda && radioPenyimpanganTidak) {
        radioPenyimpanganAda.addEventListener("change", function () {
            if (this.checked) {
                wrapPenyimpanganTable.style.display = "block";
                wrapPenyimpanganNa.style.display = "none";
            }
        });
        radioPenyimpanganTidak.addEventListener("change", function () {
            if (this.checked) {
                wrapPenyimpanganTable.style.display = "none";
                wrapPenyimpanganNa.style.display = "block";
            }
        });
    }

    // Toggle Change Control
    if (radioCcAda && radioCcTidak) {
        radioCcAda.addEventListener("change", function () {
            if (this.checked) {
                wrapCcTable.style.display = "block";
                wrapCcNa.style.display = "none";
            }
        });
        radioCcTidak.addEventListener("change", function () {
            if (this.checked) {
                wrapCcTable.style.display = "none";
                wrapCcNa.style.display = "block";
            }
        });
    }

    // Tambah baris Laporan Penyimpangan
    if (btnAddPenyimpangan && tbodyPenyimpangan) {
        btnAddPenyimpangan.addEventListener("click", function () {
            const tr = document.createElement("tr");
            tr.innerHTML = `
                <td><input type="text" name="penyimpangan_nomor[]" placeholder="Contoh: DEV/2026/001" class="tap-input-sm"></td>
                <td><input type="text" name="penyimpangan_status[]" placeholder="Contoh: Closed" class="tap-input-sm"></td>
                <td style="text-align: center;"><button type="button" class="tap-btn-remove-row" title="Hapus baris">&times;</button></td>
            `;
            tbodyPenyimpangan.appendChild(tr);
        });
    }

    // Tambah baris Change Control
    if (btnAddCc && tbodyCc) {
        btnAddCc.addEventListener("click", function () {
            const tr = document.createElement("tr");
            tr.innerHTML = `
                <td><input type="text" name="change_control_nomor[]" placeholder="Contoh: FUP/2026/001" class="tap-input-sm"></td>
                <td><input type="text" name="change_control_status[]" placeholder="Contoh: Closed" class="tap-input-sm"></td>
                <td style="text-align: center;"><button type="button" class="tap-btn-remove-row" title="Hapus baris">&times;</button></td>
            `;
            tbodyCc.appendChild(tr);
        });
    }

    // Event delegation untuk hapus baris
    document.addEventListener("click", function (e) {
        const removeBtn = e.target.closest(".tap-btn-remove-row");
        if (removeBtn) {
            const row = removeBtn.closest("tr");
            const tbody = row.parentElement;
            if (tbody.querySelectorAll("tr").length > 1) {
                row.remove();
            } else {
                row.querySelectorAll("input").forEach((inp) => (inp.value = ""));
            }
        }
    });

    document.querySelectorAll("[data-action]").forEach(function (btn) {
        btn.addEventListener("click", function () {
            openModal(
                btn.getAttribute("data-action"),
                btn.getAttribute("data-url"),
                btn.getAttribute("data-batch"),
                btn.getAttribute("data-step") || "supervisor"
            );
        });
    });

    cancelBtn.addEventListener("click", closeModal);
    backdrop.addEventListener("click", function (e) {
        if (e.target === backdrop) closeModal();
    });

    form.addEventListener("submit", function (e) {
        if (currentAction === "reject" && !noteInput.value.trim()) {
            e.preventDefault();
            errorEl.textContent = "Rejection reason is required.";
            errorEl.classList.add("show");
            return;
        }

        // Jika SPV approve dan pilih 'ada', pastikan setidaknya 1 nomor terisi
        if (currentAction === "approve" && spvFields && spvFields.style.display !== "none") {
            if (radioPenyimpanganAda && radioPenyimpanganAda.checked) {
                const nomorInputs = tbodyPenyimpangan.querySelectorAll("input[name='penyimpangan_nomor[]']");
                let hasFilled = false;
                nomorInputs.forEach((inp) => {
                    if (inp.value.trim()) hasFilled = true;
                });
                if (!hasFilled) {
                    e.preventDefault();
                    errorEl.textContent = "If Deviation Report is 'Yes', please fill in at least one Deviation No.";
                    errorEl.classList.add("show");
                    return;
                }
            }

            if (radioCcAda && radioCcAda.checked) {
                const ccInputs = tbodyCc.querySelectorAll("input[name='change_control_nomor[]']");
                let hasFilledCc = false;
                ccInputs.forEach((inp) => {
                    if (inp.value.trim()) hasFilledCc = true;
                });
                if (!hasFilledCc) {
                    e.preventDefault();
                    errorEl.textContent = "If Change Control is 'Yes', please fill in at least one FUP No.";
                    errorEl.classList.add("show");
                    return;
                }
            }
        }
    });
})();
