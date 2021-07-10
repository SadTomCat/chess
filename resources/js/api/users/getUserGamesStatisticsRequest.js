import UserRequestValidator from '../../validators/UserRequestValidator';

/**
 * Data is validated
 *
 * Successful {
 *     statistic: [
 *           count_games: int
 *           count_won: int
 *           not_count_games: int
 *     ]
 * }
 *
 * Fail {
 *     status: false,
 *     message: String,
 * }
 *
 * */
export default async (userId) => {
    const res = await window.axios.get(`/api/users/${userId}/games/statistics`)
        .catch((e) => e.response);

    if (res.status !== 200 || res.data.status === false) {
        return {
            status: false,
            message: res.data.message === undefined ? 'Something went wrong' : res.data.message,
        };
    }

    const validator = new UserRequestValidator(res.data);

    if (validator.validateGamesStatistics() === false) {
        return {
            status: false,
            message: 'Got invalid data',
        };
    }

    return res.data;
};
