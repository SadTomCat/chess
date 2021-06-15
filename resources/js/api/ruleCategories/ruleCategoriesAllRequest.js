import RuleCategoriesAfterRequestValidation from '../../validators/RuleCategoriesAfterRequestValidation';

/**
 * Data is validated
 *
 * Successful {
 *     categories [
 *         id: number
 *         name: string
 *     ]
 * }
 *
 * Fail {
 *     status: false,
 *     message: backend message | 'Something went wrong'
 * }
 * */
export default async () => {
    const res = await window.axios.get('/api/rules/categories')
        .catch((e) => e.response);

    if (res.status !== 200 || res.data.status === false) {
        return {
            status: false,
            message: res.data.message === undefined ? 'Something went wrong' : res.data.message,
        };
    }

    const validator = new RuleCategoriesAfterRequestValidation(res.data);

    if (validator.validateGetAll() === false) {
        return {
            status: false,
            message: 'Got invalid data',
        };
    }

    return res.data;
};
