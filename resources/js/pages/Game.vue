<template>
    <div class="chess-game-wrapper">

        <div class="chess-game" v-if="store.getters.CAN_SHOW_BOARD">

            <!-- Top -->
            <chess-board-top-panel></chess-board-top-panel>

            <!-- Game -->
            <div class="chess-game__game-and-chat">
                <!-- Chess board -->
                <chess-board @move="moveHandler">
                </chess-board>

                <!-- Chat -->
                <chess-board-chat></chess-board-chat>
            </div>

            <!-- Bottom -->
            <chess-board-bottom></chess-board-bottom>
        </div>

        <div class="chess-game__loading" v-else>
            <h1>Connection...</h1>
        </div>

        <teleport to="body">
            <chess-win-or-lose-pop-up :isWin="isWin" v-if="store.state.game.gameEnded"></chess-win-or-lose-pop-up>
        </teleport>
    </div>
</template>

<script>
import {
    computed,
    onBeforeMount, onBeforeUnmount,
} from 'vue';
import { useRoute } from 'vue-router';
import { useStore } from 'vuex';
import ChessBoard from '~/components/chess/ChessBoard.vue';
import ChessBoardChat from '~/components/chess/ChessBoardChat.vue';
import ChessBoardTopPanel from '~/components/chess/ChessBoardTopPanel.vue';
import ChessBoardBottom from '~/components/chess/ChessBoardBottom.vue';
import gameEventsHelper from '~/helpers/gameEventsHelper';
import gameMoveRequest from '../api/chess/gameMoveRequest';
import ChessWinOrLosePopUp from '~/components/chess/ChessWinOrLosePopUp.vue';

export default {
    name: 'Game',

    setup() {
        const route = useRoute();
        const store = useStore();

        // Need before component created
        store.commit('UNSET_GAME');
        store.commit('SET_GAME_TOKEN', route.params.token);

        const isWin = computed(() => (
            (store.state.game.color === 'white' && store.state.game.currentMoveNum % 2 === 0)
            || (store.state.game.color === 'black' && store.state.game.currentMoveNum % 2 !== 0)
        ));

        const gameEvents = gameEventsHelper();

        const moveHandler = async (move) => {
            store.commit('SET_MOVE_ERROR_MESSAGE', '');
            store.commit('SET_MOVE_HANDLING', true);
            const res = await gameMoveRequest(store.state.game.gameToken, { move });

            if (res.status === false) {
                store.commit('SET_MOVE_ERROR_MESSAGE', res.message);
                store.commit('SET_MOVE_HANDLING', false);
            }
        };

        onBeforeMount(async () => {
            window.echo.join(`game-${store.state.game.gameToken}`)
                .here(gameEvents.here)
                .listen('GameMoveEvent', gameEvents.newMove)
                .listen('GameNewMessageEvent', gameEvents.newMessage)
                .listen('GameStartEvent', gameEvents.gameStarted)
                .listen('GameEndEvent', gameEvents.gameEnded)
                .listen('GameNotStartedEvent', gameEvents.gameNotStarted)
                .error(gameEvents.echoError);
        });

        onBeforeUnmount(() => {
            window.echo.leave(`game-${store.state.game.gameToken}`);
            store.commit('UNSET_GAME');
        });

        return {
            moveHandler,
            store,
            isWin,
        };
    },

    components: {
        ChessBoard,
        ChessBoardChat,
        ChessBoardTopPanel,
        ChessBoardBottom,
        ChessWinOrLosePopUp,
    },
};
</script>

<style lang="scss">
.chess-game-wrapper {
    @apply flex justify-center py-16 px-10;
}

.chess-game {
    @apply flex flex-col;

    &__game-and-chat {
        @apply flex;
    }
}
</style>
