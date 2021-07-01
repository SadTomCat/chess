import AfterPaginationRequestValidation from '../../validators/AfterPaginationRequestValidation';

export default async (table, columns, page, perPage, ordering = false) => {
    const res = await window.axios.get(
        `/api/admin/table/${table}/paginated`,
        {
            params: {
                columns, page, perPage, ordering,
            },
            paramsSerializer: (params) => window.qs.stringify(params),
        },
    ).catch((e) => e.response);

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
