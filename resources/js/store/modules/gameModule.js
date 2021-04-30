export default {
    state: () => ({
        pageLoading: true,
        gameToken: '',
        gameStarted: false,
        gameEnded: false,
        moves: [],
        moveEndAt: 0,
        messages: [],
        chatMute: false,
        moveHandling: false,
        currentMoveNum: 0,
        color: '',
        timeEnded: false,
        moveErrorMessage: '',
        timerStarted: false,
    }),

    getters: {
        CAN_SHOW_BOARD(state, getters, rootState) {
            return !state.pageLoading && rootState.user.logged;
        },

        BOARD_LOADER_SHOWN(state) {
            return state.currentMoveNum === 0 || state.moveHandling === true || state.timeEnded === true
                || state.timerStarted === false;
        },

        CAN_MOVE_BY_COLOR(state) {
            return (state.color === 'white' && state.currentMoveNum % 2 !== 0)
                || (state.color === 'black' && state.currentMoveNum % 2 === 0);
        },

        CAN_MOVE(state, getters) {
            return state.currentMoveNum !== 0 && getters.BOARD_LOADER_SHOWN === false
                && getters.CAN_MOVE_BY_COLOR === true;
        },
    },

    mutations: {
        SET_GAME_PAGE_LOADING(state, payload) {
            state.pageLoading = payload;
        },

        SET_GAME_TOKEN(state, payload) {
            state.gameToken = payload;
        },

        SET_MOVE_HANDLING(state, payload) {
            state.moveHandling = payload;
        },

        SET_GAME_STARTED(state, payload) {
            state.gameStarted = payload;
        },

        SET_GAME_ENDED(state, payload) {
            if (payload === true) {
                state.moveHandling = false;
            }

            state.gameEnded = payload;
        },

        SET_COLOR(state, payload) {
            state.color = payload;
        },

        SET_MOVES(state, payload) {
            state.moves.length = 0;
            state.currentMoveNum = payload.length + 1;
            payload.forEach((el) => {
                state.moves.push(el);
            });
        },

        SET_MOVE_END_AT(state, payload) {
            state.moveEndAt = payload > 0 ? payload : 0;
        },

        PUSH_MOVE(state, payload) {
            state.currentMoveNum++;
            state.moves.push(payload);
        },

        SET_TIME_ENDED(state, payload) {
            state.timerStarted = false;
            state.timeEnded = payload;
        },

        SET_TIMER_STARTED(state, payload) {
            state.timerStarted = payload;
        },

        PUSH_MESSAGE(state, payload) {
            if (state.chatMute) {
                return;
            }

            state.messages.push(payload);
        },

        SWITCH_CHAT_MUTE(state) {
            state.chatMute = !state.chatMute;
        },

        SET_MOVE_ERROR_MESSAGE(state, payload) {
            state.moveErrorMessage = payload;
        },

        UNSET_GAME(state) {
            state.pageLoading = true;
            state.gameToken = '';
            state.gameStarted = false;
            state.gameEnded = false;
            state.moves = [];
            state.messages = [];
            state.moveHandling = false;
            state.currentMoveNum = 0;
            state.color = '';
            state.timeEnded = false;
            state.moveErrorMessage = '';
            state.timerStarted = false;
        },
    },
};
