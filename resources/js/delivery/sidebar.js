const storageKey = "entrega-express.sidebar-open";

const isDesktop = () => window.matchMedia("(min-width: 1024px)").matches;

export const setupSidebar = () => {
    const sidebar = document.getElementById("delivery-sidebar");
    const toggle = document.getElementById("delivery-sidebar-toggle");
    const overlay = document.getElementById("delivery-sidebar-overlay");
    const content = document.getElementById("delivery-content");

    if (!sidebar || !toggle || !overlay || !content) {
        return;
    }

    const setOpen = (open) => {
        sidebar.style.translate = open ? "0 0" : "-100% 0";
        overlay.hidden = isDesktop() || !open;
        content.style.paddingLeft = isDesktop() && open ? "18rem" : "0";
        toggle.setAttribute("aria-expanded", String(open));
        toggle.setAttribute(
            "aria-label",
            open ? "Ocultar menú" : "Mostrar menú",
        );
        document.body.classList.toggle("overflow-hidden", open && !isDesktop());
        localStorage.setItem(storageKey, String(open));
    };

    toggle.addEventListener("click", (event) => {
        event.preventDefault();
        event.stopPropagation();
        setOpen(toggle.getAttribute("aria-expanded") !== "true");
    });
    overlay.addEventListener("click", () => setOpen(false));
    window.addEventListener("resize", () =>
        setOpen(localStorage.getItem(storageKey) !== "false"),
    );
    window.addEventListener("delivery-sidebar-close", () => setOpen(false));
    setOpen(localStorage.getItem(storageKey) !== "false");
};
