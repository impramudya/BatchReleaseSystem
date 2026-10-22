/**
 * BatchReleasePro - Global Application Script & Seamless PJAX Navigator
 */

// ==========================================================================
// 1. User Profile Menu Dropdown
// ==========================================================================
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

// ==========================================================================
// 2. Mobile Responsive Sidebar Toggle
// ==========================================================================
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

// ==========================================================================
// 3. Sidebar Submenu Accordion Toggles
// ==========================================================================
function bindNavToggles() {
    document.querySelectorAll("[data-nav-toggle]").forEach(function (btn) {
        if (btn.__navToggleBound) return;
        btn.__navToggleBound = true;
        btn.addEventListener("click", function (e) {
            e.preventDefault();
            e.stopPropagation();
            const parent = btn.closest(".brp-nav-parent");
            if (parent) parent.classList.toggle("is-open");
        });
    });
}
bindNavToggles();

// ==========================================================================
// 4. Dark / Light Theme Toggle
// ==========================================================================
(function () {
    const btn = document.getElementById("theme-toggle");
    if (!btn) return;
    btn.addEventListener("click", function (e) {
        e.preventDefault();
        e.stopPropagation();
        const isDark = document.documentElement.classList.toggle("dark");
        localStorage.setItem("theme", isDark ? "dark" : "light");
    });
})();

// ==========================================================================
// 5. Seamless PJAX Partial Navigator
// ==========================================================================
(function () {
    const progressBar = {
        el: document.getElementById("brp-progress-bar"),
        timer: null,
        start() {
            if (!this.el) return;
            clearTimeout(this.timer);
            this.el.style.transition = "width 0.25s ease, opacity 0.2s ease";
            this.el.style.opacity = "1";
            this.el.style.width = "25%";
            this.timer = setTimeout(() => {
                this.el.style.width = "65%";
                this.timer = setTimeout(() => {
                    this.el.style.width = "85%";
                }, 300);
            }, 150);
        },
        done() {
            if (!this.el) return;
            clearTimeout(this.timer);
            this.el.style.width = "100%";
            setTimeout(() => {
                this.el.style.opacity = "0";
                setTimeout(() => {
                    this.el.style.transition = "none";
                    this.el.style.width = "0%";
                }, 200);
            }, 180);
        }
    };

    let isNavigating = false;

    function shouldIntercept(anchor, e) {
        if (e.defaultPrevented) return false;
        if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey || e.button !== 0) return false;

        const href = anchor.getAttribute("href");
        if (!href || href === "#" || href.startsWith("javascript:") || href.startsWith("#")) return false;

        // Origin check
        if (anchor.origin !== window.location.origin) return false;

        // Explicit opt-outs
        if (anchor.hasAttribute("download")) return false;
        if (anchor.getAttribute("target") === "_blank") return false;
        if (anchor.hasAttribute("data-no-pjax") || anchor.closest("[data-no-pjax]")) return false;

        // Skip binary downloads and exports
        const path = anchor.pathname.toLowerCase();
        if (
            path.includes("/export-excel") ||
            path.includes("/export-pdf") ||
            path.includes("/pdf-download") ||
            path.endsWith(".pdf") ||
            path.endsWith(".xlsx") ||
            path.endsWith(".csv") ||
            path.endsWith(".zip") ||
            path.includes("/logout")
        ) {
            return false;
        }

        return true;
    }

    function executePageScripts(newDoc, contentContainer) {
        const scriptsToRun = [];

        // Scripts inside #brp-page-scripts
        const newScriptsContainer = newDoc.getElementById("brp-page-scripts");
        if (newScriptsContainer) {
            newScriptsContainer.querySelectorAll("script").forEach(s => scriptsToRun.push(s));
        }

        // Scripts inside .brp-content
        if (contentContainer) {
            contentContainer.querySelectorAll("script").forEach(s => scriptsToRun.push(s));
        }

        scriptsToRun.forEach(oldScript => {
            const newScript = document.createElement("script");
            Array.from(oldScript.attributes).forEach(attr => {
                newScript.setAttribute(attr.name, attr.value);
            });

            if (oldScript.src) {
                // External script
                newScript.async = false;
                document.body.appendChild(newScript);
            } else {
                // Inline script
                try {
                    window.eval(oldScript.textContent);
                } catch (err) {
                    console.error("Error executing dynamic script:", err);
                }
            }
        });

        // Trigger DOMContentLoaded for page-specific initialization
        try {
            document.dispatchEvent(new Event("DOMContentLoaded"));
        } catch (e) {}
    }

    async function navigateTo(url, pushState = true) {
        if (isNavigating) return;

        // Prevent redundant fetch if identical URL
        if (url === window.location.href && pushState) {
            return;
        }

        isNavigating = true;
        progressBar.start();
        document.body.classList.add("brp-nav-loading");

        // Close mobile sidebar if open
        const sidebar = document.getElementById("brp-sidebar");
        const overlay = document.getElementById("sidebar-overlay");
        const hamburger = document.getElementById("sidebar-toggle");
        if (sidebar && sidebar.classList.contains("is-open")) {
            sidebar.classList.remove("is-open");
            overlay?.classList.remove("is-open");
            hamburger?.setAttribute("aria-expanded", "false");
        }

        try {
            const response = await fetch(url, {
                headers: {
                    "X-BRP-Navigation": "1",
                    "X-Requested-With": "XMLHttpRequest"
                }
            });

            // Redirected by server (e.g. login or permission error)
            if (response.redirected && response.url) {
                window.location.href = response.url;
                return;
            }

            if (!response.ok) {
                window.location.href = url;
                return;
            }

            const html = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, "text/html");

            const newContent = doc.querySelector(".brp-content");
            if (!newContent) {
                window.location.href = url;
                return;
            }

            // 1. Update Title
            if (doc.title) {
                document.title = doc.title;
            }

            // 2. Update Page Title
            const currentTitleEl = document.querySelector(".brp-page-title");
            const newTitleEl = doc.querySelector(".brp-page-title");
            if (currentTitleEl && newTitleEl) {
                currentTitleEl.innerHTML = newTitleEl.innerHTML;
            }

            // 3. Update Header Actions Slot
            const currentHeaderActions = document.getElementById("brp-header-actions-slot");
            const newHeaderActions = doc.getElementById("brp-header-actions-slot");
            if (currentHeaderActions) {
                currentHeaderActions.innerHTML = newHeaderActions ? newHeaderActions.innerHTML : "";
            }

            // 4. Dynamically load any new stylesheet links from <head>
            const existingLinks = new Set(
                Array.from(document.querySelectorAll('link[rel="stylesheet"]')).map(l => l.getAttribute("href"))
            );
            doc.querySelectorAll('link[rel="stylesheet"]').forEach(link => {
                const href = link.getAttribute("href");
                if (href && !existingLinks.has(href)) {
                    const newLink = document.createElement("link");
                    newLink.rel = "stylesheet";
                    newLink.href = href;
                    document.head.appendChild(newLink);
                }
            });

            // 5. Update Sidebar Nav (Active menu classes, badge counts, accordion states)
            const currentNav = document.querySelector(".brp-nav");
            const newNav = doc.querySelector(".brp-nav");
            if (currentNav && newNav) {
                currentNav.innerHTML = newNav.innerHTML;
                bindNavToggles();
            }

            // 6. Swap Main Content
            const currentContent = document.querySelector(".brp-content");
            currentContent.innerHTML = newContent.innerHTML;

            // 7. Update browser URL history
            if (pushState) {
                window.history.pushState({ pjax: true, url: url }, "", url);
            }

            // 8. Execute dynamic page scripts
            executePageScripts(doc, currentContent);

            // 9. Dispatch custom navigation event
            window.dispatchEvent(new CustomEvent("brp:navigated", { detail: { url: url } }));

            // 10. Scroll smoothly to top
            window.scrollTo({ top: 0, behavior: "instant" });

        } catch (err) {
            console.error("PJAX navigation failed, reloading naturally:", err);
            window.location.href = url;
        } finally {
            isNavigating = false;
            document.body.classList.remove("brp-nav-loading");
            progressBar.done();
        }
    }

    // Intercept internal link clicks
    document.addEventListener("click", function (e) {
        const anchor = e.target.closest("a");
        if (!anchor) return;

        if (shouldIntercept(anchor, e)) {
            e.preventDefault();
            navigateTo(anchor.href, true);
        }
    });

    // Handle Browser Back / Forward buttons
    window.addEventListener("popstate", function (e) {
        navigateTo(window.location.href, false);
    });

    // Export global helper for manual triggering if ever needed
    window.BRP = window.BRP || {};
    window.BRP.navigateTo = navigateTo;
})();
