export default async (userId, page) => {
    const res = await window.axios.post(`/api/paginated-user-games/${userId}`, { page })
        .catch((e) => e.response);

    if (res.status !== 200 || res.data.status === false) {
        return {
            status: false,
        };
    }

    return res.data;
};
