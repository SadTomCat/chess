export default async (token) => {
    const res = await window.axios.post(`/game/${token}/join`)
        .catch((e) => e.response);

    if (res.status !== 200 || !res.data.status) {
        return {
            status: false,
        };
    }

    return {
        status: true,
    };
};
