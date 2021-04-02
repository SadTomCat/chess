<template>
    <div class="chess-game-wrapper">

        <div class="chess-game" v-if="showTable">

            <!-- Top -->
            <chess-table-top-panel :opponent-name="'test'" :moveNum="moveNum"></chess-table-top-panel>

            <div class="chess-game__game-and-chat">
                <!-- Chess table -->
                <chess-table :color="color"
                             :canMove="canMove"
                             :tableLoading="tableLoading"
                             :opponentMove="opponentMove"
                             @move="moveHandler"
                >
                </chess-table>

                <!-- Chat -->
                <chess-table-chat :opponent-messages="messages"></chess-table-chat>
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
import ChessTable from '~/components/ChessTable.vue';
import ChessTableChat from '~/components/ChessTableChat.vue';
import ChessTableTopPanel from '~/components/ChessTableTopPanel.vue';
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
        const showTable = computed(() => !loading.value && store.state.user.logged);

        const color = ref('');

        const messages = reactive([]);

        const moving = ref(false);
        const moveNum = ref(0);
        const tableLoading = computed(() => moveNum.value === 0 || moving.value === true);

        const canMove = computed(() => moveNum.value !== 0 && tableLoading.value === false
            && ((color.value === 'white' && moveNum.value % 2 !== 0)
                || (color.value === 'black' && moveNum.value % 2 === 0)));

        const opponentMove = reactive({});

        const moveHandler = async (move) => {
            moving.value = true;
            const res = await gameMoveRequest(gameToken, { move });
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
                    moveNum.value = data.moveNum ?? 1;
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
            showTable,
            color,
            tableLoading,
            canMove,
            moveHandler,
            opponentMove,
            moveNum,
        };
    },

    components: {
        ChessTable,
        ChessTableChat,
        ChessTableTopPanel,
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
