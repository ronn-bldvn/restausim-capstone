import './bootstrap';
import './drag';

document.addEventListener("DOMContentLoaded", () => {
    const sidebar = document.getElementById("sidebar");
    const hamburgerBtn = document.getElementById("hamburger-btn");
    const sidebarTexts = document.querySelectorAll(".sidebar-text");
    const contentWrapper = document.getElementById("content-wrapper");

    let expanded = false;

    function showText() {
        sidebarTexts.forEach(t => {
            t.classList.remove("hidden");
            requestAnimationFrame(() => {
                t.classList.remove("opacity-0");
                t.classList.add("opacity-100");
            });
        });
    }

    function hideText() {
        sidebarTexts.forEach(t => {
            t.classList.add("opacity-0");
            t.classList.remove("opacity-100");
            setTimeout(() => t.classList.add("hidden"), 250);
        });
    }

    function expandSidebar() {
        expanded = true;
        sidebar.classList.add("w-60");
        sidebar.classList.remove("w-20");
        showText();

        if (window.innerWidth >= 768) {
            contentWrapper.style.marginLeft = "15rem";
        }
    }

    function collapseSidebar() {
        expanded = false;
        sidebar.classList.remove("w-60");
        sidebar.classList.add("w-20");
        hideText();

        if (window.innerWidth >= 768) {
            contentWrapper.style.marginLeft = "5rem";
        }
    }

    hamburgerBtn.addEventListener("click", () => {
        const isMobile = window.innerWidth < 768;

        if (isMobile) {
            if (!expanded) {
                // Slide in
                sidebar.classList.remove("-translate-x-full");
                sidebar.classList.add("translate-x-0");

                // Animate expand
                requestAnimationFrame(() => {
                    sidebar.classList.add("w-60");
                    sidebar.classList.remove("w-20");
                });

                showText();
                expanded = true;
            } else {
                // Slide out
                sidebar.classList.add("-translate-x-full");
                sidebar.classList.remove("translate-x-0");

                // Animate collapse
                sidebar.classList.remove("w-60");
                sidebar.classList.add("w-20");

                hideText();
                expanded = false;
            }

            return;
        }

        // Desktop logic
        expanded ? collapseSidebar() : expandSidebar();
    });
});