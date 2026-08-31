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

    if (!backdrop || !form) return;

    let currentAction = "approve";

    function openModal(action, url, batchNumber) {
        currentAction = action;
        form.setAttribute("action", url);
        noteInput.value = "";
        errorEl.classList.remove("show");
        batchLabel.textContent = "Batch: " + batchNumber;

        if (action === "approve") {
            title.textContent = "Approve Batch";
            noteLabel.textContent = "Catatan (opsional)";
            noteInput.required = false;
            submitBtn.textContent = "Approve";
            submitBtn.className = "brp-btn-primary";
        } else {
            title.textContent = "Reject Batch";
            noteLabel.textContent = "Alasan Reject (wajib diisi)";
            noteInput.required = true;
            submitBtn.textContent = "Reject";
            submitBtn.className = "tap-btn-reject";
        }

        backdrop.classList.add("open");
    }

    function closeModal() {
        backdrop.classList.remove("open");
    }

    document.querySelectorAll("[data-action]").forEach(function (btn) {
        btn.addEventListener("click", function () {
            openModal(
                btn.getAttribute("data-action"),
                btn.getAttribute("data-url"),
                btn.getAttribute("data-batch"),
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
            errorEl.textContent = "Alasan reject wajib diisi.";
            errorEl.classList.add("show");
        }
    });
})();
