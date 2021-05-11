import AfterPaginationRequestValidation from '../validators/AfterPaginationRequestValidation';

export default async (table, columns, page, perPage, ordering = false) => {
    const body = {
        columns,
        page,
        perPage,
        ordering,
    };

    const res = await window.axios.post(`/api/admin/table/${table}/pagination`, body).catch((e) => e.response);

    if (res.status !== 200 || res.data.status === false) {
        return {
            status: false,
        };
    }

    const validator = new AfterPaginationRequestValidation(res.data);

    if (validator.validate() === false) {
        return {
            status: false,
        };
    }

    return res.data;
};
