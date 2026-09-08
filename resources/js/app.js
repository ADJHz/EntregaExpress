import Alpine from "alpinejs";
import "flowbite";
import { createDeliveryApplication } from "./delivery/application";
import { setupSidebar } from "./delivery/sidebar";

window.Alpine = Alpine;
window.deliveryApp = createDeliveryApplication;

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", setupSidebar);
} else {
    setupSidebar();
}

Alpine.start();
