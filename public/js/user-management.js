(function () {
    // Search
    const input = document.getElementById("um-search-input");
    const rows = document.querySelectorAll("#um-table-body tr");
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
        const toast = document.getElementById("um-toast");
        const text = document.getElementById("um-toast-text");
        const icon = document.getElementById("um-toast-icon");

        text.textContent = message;
        toast.className = "um-toast " + type;

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

    // Ambil data flash dari Blade (di-set via window.umFlash di view)
    const flash = window.umFlash || {};

    if (flash.status) {
        showToast(flash.status, "success");
    }

    if (flash.error) {
        showToast(flash.error, "error");
    }

    if (flash.validationError) {
        showToast(flash.validationError, "error");
    }

    // Modal konfirmasi actions (unlock, reset password, delete)
    const overlay = document.getElementById("um-modal-overlay");
    const modalIcon = document.getElementById("um-modal-icon");
    const modalIconSvg = document.getElementById("um-modal-icon-svg");
    const modalTitle = document.getElementById("um-modal-title");
    const modalText = document.getElementById("um-modal-text");
    const btnCancel = document.getElementById("um-modal-cancel");
    const btnConfirm = document.getElementById("um-modal-confirm");
    let formToSubmit = null;

    const modalConfigs = {
        unlock: {
            title: "Unlock this user?",
            text: function (name) {
                return (
                    "Account access for <strong>" +
                    name +
                    "</strong> will be restored to active status and login attempt counter will be reset."
                );
            },
            confirmText: "Unlock Account",
            variant: "unlock",
            iconSvg:
                '<rect x="4.5" y="9.5" width="11" height="8" rx="1.5"/><path d="M7 9.5V6a3 3 0 016 0v1.5"/>',
        },
        reset: {
            title: "Reset password?",
            text: function (name) {
                return (
                    "Reset password for <strong>" +
                    name +
                    "</strong> to temporary password: <strong>Password@123</strong>. User will be required to change it on login."
                );
            },
            confirmText: "Reset Password",
            variant: "unlock",
            iconSvg:
                '<circle cx="7" cy="13" r="3"/><path d="M9.2 10.8L15.5 4.5M13.5 6.5l2 2M15.5 4.5l2 2"/>',
        },
        delete: {
            title: "Delete this user?",
            text: function (name) {
                return (
                    "<strong>" +
                    name +
                    "</strong> will permanently lose access. Deleted data cannot be recovered."
                );
            },
            confirmText: "Delete User",
            variant: "delete",
            iconSvg:
                '<path d="M4.5 6h11M8 6V4.5h4V6M6 6l.6 9.5h6.8L14 6" stroke-linecap="round" stroke-linejoin="round"/>',
        },
    };

    function openModal(form) {
        formToSubmit = form;
        const type = form.dataset.type || "delete";
        const name = form.dataset.name || "this user";
        const config = modalConfigs[type] || modalConfigs.delete;

        modalTitle.textContent = config.title;
        modalText.innerHTML = config.text(name);
        btnConfirm.textContent = config.confirmText;

        modalIcon.className = "um-modal-icon " + config.variant;
        modalIconSvg.innerHTML = config.iconSvg;
        btnConfirm.className = "um-modal-confirm " + config.variant;

        overlay.classList.add("show");
        document.body.style.overflow = "hidden";
    }

    function closeModal() {
        overlay.classList.remove("show");
        document.body.style.overflow = "";
        formToSubmit = null;
    }

    document.querySelectorAll(".um-confirm-trigger").forEach(function (btn) {
        btn.addEventListener("click", function () {
            openModal(btn.closest(".um-action-form"));
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
