(function () {
    // Search
    const input = document.getElementById("cm-search-input");
    const rows = document.querySelectorAll("#cm-table-body tr");
    if (input) {
        input.addEventListener("input", function () {
            const q = input.value.trim().toLowerCase();
            rows.forEach(function (row) {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(q) ? "" : "none";
            });
        });
    }

    // Toast
    function showToast(message, type) {
        const toast = document.getElementById("cm-toast");
        const text = document.getElementById("cm-toast-text");
        const icon = document.getElementById("cm-toast-icon");

        text.textContent = message;
        toast.className = "cm-toast " + type;

        icon.innerHTML =
            type === "success"
                ? '<path d="M4 10.5l4 4 8-9"/>'
                : '<path d="M6 6l8 8M14 6l-8 8"/>';

        setTimeout(function () {
            toast.classList.add("show");
        }, 50);
        setTimeout(function () {
            toast.classList.remove("show");
        }, 3500);
    }

    // Ambil data flash dari Blade (di-set via window.cmFlash di view)
    const flash = window.cmFlash || {};

    if (flash.status) {
        showToast(flash.status, "success");
    }

    if (flash.error) {
        showToast(flash.error, "error");
    }

    if (flash.validationError) {
        showToast(flash.validationError, "error");
    }

    // Modal konfirmasi delete
    const overlay = document.getElementById("cm-modal-overlay");
    const modalName = document.getElementById("cm-modal-name");
    const btnCancel = document.getElementById("cm-modal-cancel");
    const btnConfirm = document.getElementById("cm-modal-confirm");
    let formToSubmit = null;

    function openModal(form) {
        formToSubmit = form;
        modalName.textContent = form.dataset.name;
        overlay.classList.add("show");
        document.body.style.overflow = "hidden";
    }

    function closeModal() {
        overlay.classList.remove("show");
        document.body.style.overflow = "";
        formToSubmit = null;
    }

    document.querySelectorAll(".cm-delete-trigger").forEach(function (btn) {
        btn.addEventListener("click", function () {
            openModal(btn.closest(".cm-delete-form"));
        });
    });

    btnCancel.addEventListener("click", closeModal);

    overlay.addEventListener("click", function (e) {
        if (e.target === overlay) closeModal();
    });

    document.addEventListener("keydown", function (e) {
        if (e.key === "Escape" && overlay.classList.contains("show"))
            closeModal();
    });

    btnConfirm.addEventListener("click", function () {
        if (formToSubmit) formToSubmit.submit();
    });
})();
