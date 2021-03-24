<template>
    <div class="chess-game-wrapper">

        <div class="chess-game" v-if="canPlay">

            <!-- Top -->
            <chess-table-top-panel :opponent-name="'test'"></chess-table-top-panel>

            <div class="chess-game__game-and-chat">
                <!-- Chess table -->
                <chess-table></chess-table>

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
import ChessTable from '../components/ChessTable.vue';
import ChessTableChat from '../components/ChessTableChat.vue';
import ChessTableTopPanel from '../components/ChessTableTopPanel.vue';

export default {
    name: 'Game',

    setup() {
        const route = useRoute();
        const router = useRouter();
        const store = useStore();

        const gameToken = route.params.token;

        const loading = ref(true);

        const messages = reactive([]);

        const canPlay = computed(() => !loading.value && store.state.user.logged);

        onBeforeMount(async () => {
            echo.join(`game-${gameToken}`)
                .subscribed(() => {
                    loading.value = false;
                })
                .listen('GameMoveEvent', (data) => {
                    console.log(data);
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
                .error((e) => {
                    console.log(e);
                    router.replace('/');
                });
        });

        onBeforeUnmount(() => {
            echo.leave(`game-${gameToken}`);
        });

        return { loading, messages, canPlay };
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
