import ChessRuleNamesAfterRequestValidation from '../../validators/ChessRuleNamesAfterRequestValidation';

/**
 * chessRuleNamesAllRequest
 *
 * Data is validated
 *
 * Successful {
 *     names_info [
 *         id: number
 *         name: string
 *         slug: string
 *     ]
 * }
 *
 * Fail {
 *     status: false,
 *     message: backend message | 'Something went wrong'
 * }
 * */
export default async (whereContentFilled = false) => {
    const res = await window.axios.get(`/api/chess-rules/names?where_content_filled=${whereContentFilled}`)
        .catch((e) => e.response);

    if (res.status !== 200 || res.data.status === false) {
        return {
            status: false,
            message: res.data.message === undefined ? 'Something went wrong' : res.data.message,
        };
    }

    const validator = new ChessRuleNamesAfterRequestValidation(res.data);

    if (validator.validateGetAll() === false) {
        return {
            status: false,
            message: 'Got invalid data',
        };
    }

    return res.data;
};
