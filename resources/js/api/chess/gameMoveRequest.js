export default async (token, data) => {
    const res = await window.axios.post(`/game/${token}/move`, data)
        .catch((e) => e.response);

    if (res.status !== 200 || !res.data.status) {
        return {
            message: res.data.message ?? 'What went wrong',
            status: false,
        };
    }

    return {
        status: true,
    };
};
