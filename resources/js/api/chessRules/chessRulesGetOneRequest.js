import RulesAfterRequestValidation from '../../validators/RulesAfterRequestValidation';

/**
 * Data is validated
 *
 * Successful {
 *     exists: true
 *     content: string
 * }
 *
 * Fail {
 *     status: false,
 *     message: backend message | 'Something went wrong'
 * }
 * */
export default async (slug) => {
    const res = await window.axios.get(`/api/chess-rules/${slug}`)
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
