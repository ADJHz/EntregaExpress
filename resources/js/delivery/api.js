const request = async (url, message) => {
    const response = await fetch(url, {
        headers: { Accept: "application/json" },
    });

    if (!response.ok) {
        throw new Error(message);
    }

    return (await response.json()).data;
};

const receive = async (url, csrfToken) => {
    const response = await fetch(`${url}?resume=1`, {
        method: "POST",
        headers: {
            Accept: "text/html",
            "X-CSRF-TOKEN": csrfToken,
        },
        credentials: "same-origin",
    });

    if (!response.ok) {
        throw new Error("No fue posible registrar la recepción.");
    }

    return response.text();
};

export const createDeliveryApi = (endpoints) => ({
    pending: (search) =>
        request(
            `${endpoints.pending}?search=${encodeURIComponent(search)}`,
            "No fue posible consultar pendientes.",
        ),
    received: (search) =>
        request(
            `${endpoints.received}?search=${encodeURIComponent(search)}`,
            "No fue posible consultar entregas realizadas.",
        ),
    receive,
});
