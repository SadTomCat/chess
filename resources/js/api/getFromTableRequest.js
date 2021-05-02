export default async (table, columns, page, perPage) => {
    const res = await window.axios.post('/api/admin/table-pagination', {
        table, columns, page, perPage,
    }).catch((e) => e.response);

    if (res.status !== 200 || res.data.status === false) {
        return {
            status: false,
        };
    }

    return res.data;
};
