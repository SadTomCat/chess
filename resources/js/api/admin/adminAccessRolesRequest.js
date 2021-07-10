/**
 * @typedef AdminAccessRolesResponse
 * @type Object
 * @property {string[]} roles
 * */

/**
 * @return Promise<AdminAccessRolesResponse|FailResponse>
 * */
export default async () => {
    const res = await window.axios.get('/api/admin/roles')
        .catch((e) => e.response);

    if (res.data.status === false || res.status !== 200) {
        return window.getFailResponse(res.data.message);
    }

    if (window.isArrayOf(window.isString, res.data.roles) === false) {
        return window.getFailResponse(res.data.message);
    }

    return res.data;
};
