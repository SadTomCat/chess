/**
 * Successful {
 *      game: {
 *          end_at:       String
 *          id:           int
 *          start_at:     String
 *          token:        String
 *          winner_color: String
 *      },
 *      moves: [
 *          {
 *              from: {x: int, y: int}
 *              to:   {}
 *          }
 *      ],
 *      users: {
 *          black: {
 *              blocked:     bool
 *              count_games: int
 *              count_won:   int
 *              name:        String
 *          }
 *          white: {}
 *      }
 * }
 *
 * Fail {
 *     status: false
 *     message: String
 *     unauthorized: Boolean
 * }
 * */
export default async (gameId) => {
    const res = await window.axios.get(`/api/games/${gameId}`).catch((e) => e.response);

    if (res.data.status === false || res.status !== 200) {
        return {
            status: false,
            message: res.data.message ?? 'Something went wrong',
            unauthorized: res.status === 403 || res.status === 401,
        };
    }

    return res.data;
};
