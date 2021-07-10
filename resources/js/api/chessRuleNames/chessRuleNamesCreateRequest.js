import validationHelper from '../../helpers/validationHelper';

/**
 * Data is validated
 *
 * Successful {
 *     id: number
 * }
 *
 * Fail {
 *     status: false,
 *     message: backend message | 'Something went wrong'
 * }
 * * */
export default async (name) => {
    const res = await window.axios.post('/api/chess-rules/names', { name })
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
