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

                if (parentId) {
                    title.textContent = "Tambah Sub-Pertanyaan";
                    context.textContent = "Sub dari: " + parentLabel;
                } else {
                    title.textContent = "Tambah Pertanyaan";
                    context.textContent =
                        context.getAttribute("data-default-text") ||
                        context.textContent;
                }

                document.getElementById("add-question").value = "";
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
