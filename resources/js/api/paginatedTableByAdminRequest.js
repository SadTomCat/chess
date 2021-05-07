export default async (table, columns, page, perPage, ordering = false) => {
    const body = {
        table,
        columns,
        page,
        perPage,
        ordering,
    };

    const res = await window.axios.post('/api/admin/table-pagination', body).catch((e) => e.response);

    if (res.status !== 200 || res.data.status === false) {
        return {
            status: false,
        };
    }

    return res.data;
};
