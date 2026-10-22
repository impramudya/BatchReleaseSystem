(function () {
    function openModal(id) {
        const modal = document.getElementById(id);
        if (modal) modal.classList.add("is-open");
    }

    function closeModal(modal) {
        modal.classList.remove("is-open");
    }

    let pendingDeleteForm = null;

    document.addEventListener("click", function (e) {
        const opener = e.target.closest("[data-modal-target]");
        if (opener) {
            const targetId = opener.getAttribute("data-modal-target");

            if (targetId === "modal-edit-category") {
                const form = document.getElementById("form-edit-category");
                form.action = opener.getAttribute("data-action");
                form.querySelector("#edit-code").value =
                    opener.getAttribute("data-code") || "";
                form.querySelector("#edit-name").value =
                    opener.getAttribute("data-name") || "";
            }

            if (targetId === "modal-edit-question") {
                const form = document.getElementById("form-edit-question");
                form.action = opener.getAttribute("data-action");
                form.querySelector("#edit-question").value =
                    opener.getAttribute("data-question") || "";
                
                const depth = parseInt(opener.getAttribute("data-depth") || "0", 10);
                const wrapEditDetails = document.getElementById("wrap-edit-question-details");
                const detailsField = form.querySelector("#edit-question-details");

                if (depth > 0) {
                    if (wrapEditDetails) wrapEditDetails.style.display = "block";
                    if (detailsField) detailsField.value = opener.getAttribute("data-details") || "";
                } else {
                    if (wrapEditDetails) wrapEditDetails.style.display = "none";
                    if (detailsField) detailsField.value = "";
                }

                form.querySelector("#edit-question-status").value =
                    opener.getAttribute("data-status") || "active";
            }

            if (targetId === "modal-add-question") {
                const parentId = opener.getAttribute("data-parent-id") || "";
                const parentLabel =
                    opener.getAttribute("data-parent-label") || "";

                document.getElementById("add-question-parent-id").value =
                    parentId;

                const title = document.getElementById("add-question-title");
                const context = document.getElementById("add-question-context");
                const textarea = document.getElementById("add-question");
                const wrapAddDetails = document.getElementById("wrap-add-question-details");
                const detailsTextarea = document.getElementById("add-question-details");

                if (parentId) {
                    title.textContent = "Tambah Sub-Pertanyaan (Child)";
                    context.textContent = "Sub dari (Parent): " + parentLabel;
                    textarea.placeholder = "Contoh: a. Kelengkapan pengisian data...";
                    if (wrapAddDetails) wrapAddDetails.style.display = "block";
                } else {
                    title.textContent = "Tambah Pertanyaan Utama (Parent)";
                    context.textContent =
                        context.getAttribute("data-default-text") ||
                        context.textContent;
                    textarea.placeholder = "Contoh: 1. Cover Prosedur Pengolahan Induk...";
                    if (wrapAddDetails) wrapAddDetails.style.display = "none";
                }

                document.getElementById("add-question").value = "";
                if (detailsTextarea) detailsTextarea.value = "";
                document.getElementById("add-question-status").value = "active";
            }

            openModal(targetId);
            return;
        }

        const closer = e.target.closest("[data-modal-close]");
        if (closer) {
            const modal = closer.closest(".ccfg-modal-overlay");
            if (modal) closeModal(modal);
            if (modal && modal.id === "modal-confirm-delete") {
                pendingDeleteForm = null;
            }
            return;
        }

        if (e.target.classList.contains("ccfg-modal-overlay")) {
            closeModal(e.target);
            if (e.target.id === "modal-confirm-delete") {
                pendingDeleteForm = null;
            }
        }
    });

    document.addEventListener("DOMContentLoaded", function () {
        const context = document.getElementById("add-question-context");
        if (context) {
            context.setAttribute("data-default-text", context.textContent);
        }
    });

    document.addEventListener("submit", function (e) {
        const form = e.target;
        if (!form.classList.contains("ccfg-delete-form")) return;

        if (form.dataset.confirmed === "true") return;

        e.preventDefault();
        pendingDeleteForm = form;

        const message =
            form.getAttribute("data-confirm-message") ||
            "Yakin ingin menghapus data ini?";
        document.getElementById("confirm-delete-message").textContent = message;

        openModal("modal-confirm-delete");
    });

    document.addEventListener("click", function (e) {
        if (e.target.id !== "confirm-delete-btn") return;
        if (!pendingDeleteForm) return;

        pendingDeleteForm.dataset.confirmed = "true";
        pendingDeleteForm.submit();
        pendingDeleteForm = null;

        closeModal(document.getElementById("modal-confirm-delete"));
    });

    document.addEventListener("keydown", function (e) {
        if (e.key === "Escape") {
            document
                .querySelectorAll(".ccfg-modal-overlay.is-open")
                .forEach(closeModal);
            pendingDeleteForm = null;
        }
    });
})();
