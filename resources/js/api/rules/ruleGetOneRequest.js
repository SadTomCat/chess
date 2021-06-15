import RulesAfterRequestValidation from '../../validators/RulesAfterRequestValidation';

/**
 * Data is validated
 *
 * Successful {
 *     exists: true
 *     content: string
 * }
 *
 * Successful {
 *     exists: false
 * }
 *
 * Fail {
 *     status: false,
 *     message: backend message | 'Something went wrong'
 * }
 * */
export default async (categoryName) => {
    const res = await window.axios.get(`/api/rules/${categoryName}`)
        .catch((e) => e.response);

    if (res.status !== 200 || res.data.status === false) {
        return {
            status: false,
            message: res.data.message === undefined ? 'Something went wrong' : res.data.message,
        };
    }

    const validator = new RulesAfterRequestValidation(res.data);

    if (validator.validateGetOne() === false) {
        return {
            status: false,
            message: 'Got invalid data',
        };
    }

    return res.data;
};
