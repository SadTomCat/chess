export default async (id) => {
    const res = await window.axios.get(`/api/admin/games/${id}`)
        .catch((e) => e.response);

    if (res.data.status === false || res.status !== 200) {
        return {
            status: false,
            message: res.data.message ?? 'Something went wrong',
        };
    }

    return res.data;
};
