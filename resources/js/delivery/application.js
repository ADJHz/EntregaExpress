import { createDeliveryApi } from "./api";

export const createDeliveryApplication = (endpoints) => {
    const api = createDeliveryApi(endpoints);

    return {
        endpoints,
        pendingEmployees: [],
        receivedEmployees: [],
        query: "",
        selected: null,
        dropdownOpen: false,
        receivedQuery: "",
        loadingPending: true,
        loadingReceived: true,
        loadingAction: false,
        loadingReport: false,
        error: "",
        activeSection: "buscar",
        sidebarOpen: false,
        get filteredEmployees() {
            return this.pendingEmployees;
        },
        async init() {
            await Promise.all([this.loadPending(), this.loadReceived()]);
        },
        async loadPending() {
            this.loadingPending = true;
            try {
                this.pendingEmployees = await api.pending(this.query);
            } catch (error) {
                this.error = error.message;
            } finally {
                this.loadingPending = false;
            }
        },
        async loadReceived() {
            this.loadingReceived = true;
            try {
                this.receivedEmployees = await api.received(this.receivedQuery);
            } catch (error) {
                this.error = error.message;
            } finally {
                this.loadingReceived = false;
            }
        },
        async searchPending() {
            this.dropdownOpen = true;
            await this.loadPending();
        },
        async searchReceived() {
            await this.loadReceived();
        },
        choose(employee) {
            this.selected = employee;
            this.query = employee.nombre;
            this.dropdownOpen = false;
        },
        clearSelection() {
            this.selected = null;
            this.query = "";
            this.dropdownOpen = false;
        },
        showSection(section) {
            this.activeSection = section;
            this.sidebarOpen = false;
            this.dropdownOpen = false;
            window.dispatchEvent(new Event("delivery-sidebar-close"));
        },
        submitDelivery(event) {
            this.loadingAction = true;
            event.target.submit();
        },
        downloadReport(event) {
            this.loadingReport = true;
            event.target.submit();
            window.setTimeout(() => {
                this.loadingReport = false;
            }, 1200);
        },
    };
};
