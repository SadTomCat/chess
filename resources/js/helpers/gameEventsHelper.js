import { useStore } from 'vuex';
import { useRouter } from 'vue-router';
import joinedToGameRequest from '../api/joinedToGameRequest';

export default () => {
    const store = useStore();
    const router = useRouter();

    const here = async (data) => {
        store.commit('SET_PAGE_LOADING', false);

        const user = data.filter((el) => el.id === store.state.user.id);
        store.commit('SET_COLOR', user[0].color);
        const res = await joinedToGameRequest(store.state.game.gameToken);

        if (res.status === false) {
            await router.replace('/');
            return;
        }

        if (res.gameStarted === true && res.endAt !== undefined && res.endAt !== 0) {
            store.commit('SET_MOVE_END_AT', res.endAt);
        }

        store.commit('SET_MOVES', res.moves);
        store.commit('SET_GAME_STARTED', res.gameStarted);
    };

    const newMove = (data) => {
        store.commit('SET_MOVE_END_AT', data.endAt);
        store.commit('PUSH_MOVE', data.move);
        store.commit('SET_MOVE_HANDLING', false);
        store.commit('SET_TIME_ENDED', false);
    };

    const newMessage = (data) => {
        store.commit('PUSH_MESSAGE', {
            message: data.message,
            fromOpponent: true,
        });
    };

    const gameStarted = (data) => {
        store.commit('SET_MOVE_END_AT', data.endAt);
        store.commit('SET_GAME_STARTED', true);
    };

    const gameEnded = (data) => {
        if (data.move.from !== undefined && data.move.to !== undefined) {
            store.commit('PUSH_MOVE', data.move);
        }

        store.commit('SET_GAME_ENDED', true);
    };

    const gameNotStarted = () => {
        router.replace('/');
    };

    const echoError = (e) => {
        console.log(e);
        window.echo.leave(`game-${store.state.game.gameToken}`);
        router.replace('/');
    };

    return {
        here,
        newMove,
        newMessage,
        gameStarted,
        gameEnded,
        gameNotStarted,
        echoError,
    };
};
