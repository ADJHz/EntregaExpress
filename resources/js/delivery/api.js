const request = async (url, message) => {
    const response = await fetch(url, {
        headers: { Accept: "application/json" },
    });

    if (!response.ok) {
        throw new Error(message);
    }

    return (await response.json()).data;
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
});
