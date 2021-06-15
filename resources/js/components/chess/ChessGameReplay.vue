<template>
    <div class="chess-game-replay">
        <chess-board :moves="moves"></chess-board>

        <div class="chess-game-replay__right">
            <div class="chess-game-replay__button-panel">
                <span class="material-icons" @click="prev">arrow_back</span>
                <span class="material-icons" @click="next">arrow_forward</span>
            </div>
        </div>
    </div>
</template>

<script>
import { useStore } from 'vuex';
import { onBeforeMount, onBeforeUnmount, watch } from 'vue';
import ChessBoard from '~/components/chess/ChessBoard.vue';

export default {
    name: 'ChessGameReplay',

    props: {
        loading: Boolean,

        moves: Array,
    },

    setup(props) {
        const store = useStore();
        let printed = 0;

        const prev = () => {
            if (printed === 0) {
                return;
            }

            store.commit('REMOVE_LAST');
            printed--;
        };

        const next = () => {
            if (printed === props.moves.length) {
                return;
            }

            store.commit('PUSH_MOVE', props.moves[printed]);
            printed++;
        };

        watch(props.moves, () => {
            if (props.moves.length > 0) {
                store.commit('START_REPLAY');
            }
        });

        onBeforeMount(() => {
            store.commit('UNSET_GAME');
        });

        onBeforeUnmount(() => {
            store.commit('UNSET_GAME');
        });

        return {
            prev,
            next,
        };
    },

    components: { ChessBoard },
};
</script>

<style lang="scss">
.chess-game-replay {
    @apply flex rounded-2xl overflow-hidden shadow bg-white w-full;

    &__right {
        @apply ml-6 w-full;
    }

    &__button-panel {
       @apply flex h-12 w-full space-x-10;

        span {
            @apply text-5xl cursor-pointer;
        }
    }
}

</style>
