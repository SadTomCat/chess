<template>
    <div class="chess-game-wrapper">

        <div class="chess-game" v-if="showBoard">

            <!-- Top -->
            <chess-board-top-panel :canMoveByColor="canMoveByColor"
                                   :moveNum="moveNum"
                                   @timeEnded="timeEndedHandler"
            >
            </chess-board-top-panel>

            <div class="chess-game__game-and-chat">
                <!-- Chess board -->
                <chess-board :color="color"
                             :moves="moves"
                             :canMove="canMove"
                             :boardLoading="boardLoading"
                             :opponentMove="opponentMove"
                             @move="moveHandler"
                >
                </chess-board>

                <!-- Chat -->
                <chess-board-chat :opponent-messages="messages"></chess-board-chat>
            </div>

        </div>

        <div class="chess-game__loading" v-else>
            <h1>Connection...</h1>
        </div>

    </div>
</template>

<script>
import {
    onBeforeMount, onBeforeUnmount, ref, reactive, computed,
} from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useStore } from 'vuex';
import ChessBoard from '~/components/ChessBoard.vue';
import ChessBoardChat from '~/components/ChessBoardChat.vue';
import ChessBoardTopPanel from '~/components/ChessBoardTopPanel.vue';
import gameMoveRequest from '~/api/gameMoveRequest';
import joinedToGameRequest from '~/api/joinedToGameRequest';

export default {
    name: 'Game',

    setup() {
        const route = useRoute();
        const router = useRouter();
        const store = useStore();
        const loading = ref(true);
        const gameToken = route.params.token;
        const showBoard = computed(() => !loading.value && store.state.user.logged);
        let gameStarted = false;

        const color = ref('');

        const moves = reactive([]);

        const messages = reactive([]);

        const timeEnded = ref(false);
        const moving = ref(false);
        const moveNum = ref(0);
        const boardLoading = computed(() => moveNum.value === 0 || moving.value === true
            || timeEnded.value === true);

        const canMoveByColor = computed(() => ((color.value === 'white' && moveNum.value % 2 !== 0)
            || (color.value === 'black' && moveNum.value % 2 === 0)));

        const canMove = computed(() => moveNum.value !== 0 && boardLoading.value === false
            && canMoveByColor.value === true);

        const opponentMove = reactive({});

        const moveHandler = async (move) => {
            moving.value = true;
            const res = await gameMoveRequest(gameToken, { move });

            if (res.status === false) {
                moving.value = res.status;
            }
        };

        const timeEndedHandler = () => {
            timeEnded.value = true;
        };

        onBeforeMount(async () => {
            window.echo.join(`game-${gameToken}`)
                .here(async (data) => {
                    loading.value = false;

                    const user = data.filter((el) => el.id === store.state.user.id);
                    color.value = user[0].color;
                    await joinedToGameRequest(gameToken);
                })
                .listen('GameMoveEvent', (move) => {
                    opponentMove.from = move.from;
                    opponentMove.to = move.to;
                    moveNum.value++;
                    moving.value = false;
                    timeEnded.value = false;
                })
                .listen('GameIncorrectMoveEvent', (data) => {
                    console.log(data);
                })
                .listen('GameNewMessageEvent', (data) => {
                    messages.push({
                        message: data.message,
                        fromOpponent: true,
                    });
                })
                .listen('GameStartEvent', (data) => {
                    if (gameStarted === false && data.moves !== undefined && moves.length === 0) {
                        data.moves.forEach((el) => {
                            moves.push(el);
                        });

                        moveNum.value = data.moves.length + 1;
                        gameStarted = true;
                    }
                })
                .error((e) => {
                    console.log(e);
                    window.echo.leave(`game-${gameToken}`);
                    router.replace('/');
                });
        });

        onBeforeUnmount(() => {
            window.echo.leave(`game-${gameToken}`);
        });

        return {
            loading,
            messages,
            showBoard,
            color,
            moves,
            boardLoading,
            canMoveByColor,
            canMove,
            moveHandler,
            timeEndedHandler,
            opponentMove,
            moveNum,
        };
    },

    components: {
        ChessBoard,
        ChessBoardChat,
        ChessBoardTopPanel,
    },
};
</script>

<style lang="scss">
.chess-game-wrapper {
    @apply flex justify-center py-24 px-10;
}

.chess-game {
    @apply flex flex-col;

    &__game-and-chat {
        @apply flex;
    }
}
</style>
