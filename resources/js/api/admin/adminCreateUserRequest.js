/**
 * @param {object} user
 * @param {?string} user.name
 * @param {string} user.email
 * @param {string} user.role
 * @param {string} user.password
 * @param {string} adminPassword
 * @return Promise<SuccessfulResponse|FailResponse>
 * */
export default async (user, adminPassword) => {
    const userData = {};

    Object.getOwnPropertyNames(user).forEach((p) => {
        if (user[p] !== undefined && user[p] !== '') {
            userData[p] = user[p];
        }
    });

    const data = { ...userData, admin_password: adminPassword };

    const res = await window.axios.post('/api/admin/users/create', data)
        .catch((e) => e.response);

    if (res.data.status === true) {
        return res.data;
    }

    return window.getFailResponse(res.data.message);
};
