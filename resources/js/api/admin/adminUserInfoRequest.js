/**
 * @param {string|number} idOrEmail
 * @param {boolean} needStatistics
 * @param {string[]} columns default return all access
 * */
export default async (idOrEmail, needStatistics = true, columns = []) => {
    const params = { need_statistics: needStatistics };

    if (window.isArrayOf(window.isString, columns) === true && columns.length > 0) {
        params.columns = columns;
    }

    const res = await window.axios.get(`/api/admin/users/${idOrEmail}`, {
        params,
        paramsSerializer: (queryParams) => window.qs.stringify(queryParams),
    }).catch((e) => e.response);

    if (res.data.status === false || res.status !== 200) {
        return {
            status: false,
            message: res.data.message ?? 'Something went wrong',
        };
    }

    return res.data;
};
