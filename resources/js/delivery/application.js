import { createDeliveryApi } from "./api";

const stateStorageKey = "entrega-express.delivery-state";
const queueStorageKey = "entrega-express.receive-queue";

const readStorage = (key, fallback) => {
    try {
        return JSON.parse(localStorage.getItem(key)) ?? fallback;
    } catch {
        return fallback;
    }
};

const writeStorage = (key, value) => {
    try {
        localStorage.setItem(key, JSON.stringify(value));
    } catch {
        return false;
    }

    return true;
};

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
        isOnline: navigator.onLine,
        pendingReceipts: [],
        syncingQueue: false,
        syncRetryTimer: null,
        receivedLoaded: false,
        get filteredEmployees() {
            return this.pendingEmployees;
        },
        async init() {
            const savedState = readStorage(stateStorageKey, {});

            this.query = savedState.query ?? "";
            this.selected = savedState.selected ?? null;
            this.activeSection = savedState.activeSection ?? "buscar";
            this.pendingReceipts = readStorage(queueStorageKey, []);
            window.addEventListener("online", () => this.handleOnline());
            window.addEventListener("offline", () => {
                this.isOnline = false;
                this.error = "Sin conexión. El avance se conservará en este equipo.";
            });

            await this.loadPending();

            if (this.activeSection === "recibidos") {
                await this.loadReceived();
            }

            await this.syncQueuedReceipts();
        },
        async loadPending() {
            if (!this.isOnline) {
                this.loadingPending = false;
                return;
            }

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
            if (!this.isOnline) {
                this.loadingReceived = false;
                return;
            }

            this.loadingReceived = true;
            try {
                this.receivedEmployees = await api.received(this.receivedQuery);
                this.receivedLoaded = true;
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
            this.persistState();
        },
        clearSelection() {
            this.selected = null;
            this.query = "";
            this.dropdownOpen = false;
            this.persistState();
        },
        clearReceiptState() {
            this.clearSelection();
        },
        showSection(section) {
            this.activeSection = section;
            this.dropdownOpen = false;
            this.persistState();

            if (section === "recibidos" && !this.receivedLoaded) {
                this.loadReceived();
            }
        },
        persistState() {
            writeStorage(stateStorageKey, {
                query: this.query,
                selected: this.selected,
                activeSection: this.activeSection,
            });
        },
        persistQueue() {
            writeStorage(queueStorageKey, this.pendingReceipts);
        },
        queueReceipt(employee) {
            if (!this.pendingReceipts.some((receipt) => receipt.id === employee.id)) {
                this.pendingReceipts.push({
                    id: employee.id,
                    name: employee.nombre,
                    url: employee.receive_url,
                });
                this.persistQueue();
            }

            this.error = `La recepción de ${employee.nombre} quedó pendiente. Se enviará al recuperar la conexión.`;
        },
        async handleOnline() {
            this.isOnline = true;
            this.error = "";

            if (this.syncRetryTimer) {
                window.clearTimeout(this.syncRetryTimer);
                this.syncRetryTimer = null;
            }

            await Promise.all([this.loadPending(), this.loadReceived()]);
            await this.syncQueuedReceipts();
        },
        async syncQueuedReceipts() {
            if (!this.isOnline || this.syncingQueue || this.pendingReceipts.length === 0) {
                return;
            }

            const csrfToken = document.querySelector('input[name="_token"]')?.value;

            if (!csrfToken) {
                this.error = "No se encontró la sesión de seguridad. Recarga la página cuando haya conexión.";
                return;
            }

            this.syncingQueue = true;

            try {
                for (const receipt of this.pendingReceipts) {
                    const html = await api.receive(receipt.url, csrfToken);

                    this.pendingReceipts = this.pendingReceipts.filter(
                        (queuedReceipt) => queuedReceipt.id !== receipt.id,
                    );
                    this.persistQueue();
                    this.clearReceiptState();
                    document.open();
                    document.write(html);
                    document.close();
                    return;
                }
            } catch {
                this.isOnline = navigator.onLine;
                this.error = "La conexión sigue inestable. La recepción permanece guardada en este equipo.";
                this.syncRetryTimer = window.setTimeout(() => {
                    this.syncRetryTimer = null;
                    this.syncQueuedReceipts();
                }, 5000);
            } finally {
                this.syncingQueue = false;
            }
        },
        async submitDelivery(event) {
            event.preventDefault();

            if (!this.selected || this.loadingAction) {
                return;
            }

            if (!this.isOnline) {
                this.queueReceipt(this.selected);
                return;
            }

            this.loadingAction = true;

            try {
                const csrfToken = event.target.querySelector('input[name="_token"]')?.value;

                if (!csrfToken) {
                    throw new Error("No se encontró la sesión de seguridad.");
                }

                const html = await api.receive(this.selected.receive_url, csrfToken);
                this.clearReceiptState();
                document.open();
                document.write(html);
                document.close();
            } catch {
                this.isOnline = navigator.onLine;
                this.queueReceipt(this.selected);
            } finally {
                this.loadingAction = false;
            }
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
