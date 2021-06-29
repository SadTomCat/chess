<template>
    <div class="view-game">
        <h1 class="view-game__title">Replay</h1>

        <chess-game-replay :loading="loading" :moves="moves"></chess-game-replay>
    </div>
</template>

<script>
import { ref } from 'vue';
import ChessGameReplay from '../components/chess/ChessGameReplay.vue';

export default {
    name: 'ViewGame',

    setup() {
        const loading = ref(true);
        const moves = ref([]);

        return {
            loading,
            moves,
        };
    },

    components: { ChessGameReplay },
};
</script>

<style lang="scss">
.view-game {
    @apply py-16 px-32;

    &__title {
        @apply text-4xl mb-10;
    }
}
</style>
