/**
 * @param {number|string} idOrEmail
 * @param {string} role
 * @param {string} adminPassword
 * @return Promise<SuccessfulResponse|FailResponse>
 * */
export default async (idOrEmail, role, adminPassword) => {
    const data = { role, admin_password: adminPassword };

    const res = await window.axios.patch(`/api/admin/users/${idOrEmail}/roles`, data)
        .catch((e) => e.response);

    if (res.data.status === true) {
        return res.data;
    }

    return window.getFailResponse(res.data.message);
};
