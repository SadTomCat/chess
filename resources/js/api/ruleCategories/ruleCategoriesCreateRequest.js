import validationHelper from '../../helpers/validationHelper';

export default async (name) => {
    const res = await window.axios.post('/api/rules/categories', { name })
        .catch((e) => e.response);

    if (res.status !== 200 || res.data.status === false) {
        return {
            status: false,
            message: res.data.message === undefined ? 'Something went wrong' : res.data.message,
        };
    }

    const { validateColumns } = validationHelper();
    const validated = validateColumns(res.data, [{ name: 'id', type: 'number' }]);

    if (validated.status === false) {
        return {
            status: false,
            message: validated.message,
        };
    }

    return res.data;
};
