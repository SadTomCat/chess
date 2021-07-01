import { ref } from 'vue';

/**
 * @param {ViewGameGameInfo|Function} GameInfoFormatter
 * @param {ViewGameUserInfo|Function} UserInfoFormatter
 * */
export default (GameInfoFormatter, UserInfoFormatter) => {
    const loading = ref(true);

    const gameInfo = ref(new GameInfoFormatter());
    const winnerInfo = ref(new UserInfoFormatter());
    const loserInfo = ref(new UserInfoFormatter());
    const moves = ref([]);

    /**
     * @param {Object} usersInfo
     * @param {Object} [usersInfo.winner_color]
     * @param {Object} localGameInfo
     * */
    const setUsersInfo = (usersInfo, localGameInfo) => {
        const winnerColor = localGameInfo.winner_color ?? 'white';
        const loserColor = winnerColor === 'white' ? 'black' : 'white';

        winnerInfo.value = new UserInfoFormatter(usersInfo[winnerColor], winnerColor, winnerColor);
        loserInfo.value = new UserInfoFormatter(usersInfo[loserColor], loserColor, winnerColor);
    };

    /**
     * @param {Object} data
     * @param {Object} data.users
     * @param {Object} data.game
     * @param {Array} data.moves
     * */
    const afterRequestAction = (data) => {
        setUsersInfo(data.users, data.game);
        gameInfo.value = new GameInfoFormatter(data.game, data.users, data.moves.length);

        moves.value = data.moves;

        loading.value = false;
    };

    return {
        loading,
        gameInfo,
        winnerInfo,
        loserInfo,
        moves,
        setUsersInfo,
        afterRequestAction,
    };
};
