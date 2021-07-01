export default async (gameToken, message) => {
    const res = await window.axios.post(`/game/${gameToken}/message`, { message })
        .catch((e) => e.response);

    if (res.status !== 200 || res.data.status === false) {
        return {
            status: false,
            message: res.data.message,
        };
    }

    return { status: true };
};
