<template>
    <div class="chess-board-top-panel">
        <p>
            <span class="chess-board-top-panel__opponent-name">Moving: </span>
            {{ $store.getters.CAN_MOVE_BY_COLOR ? 'you' : 'opponent' }}
        </p>

        <chess-timer></chess-timer>
    </div>
</template>

<script>
import ChessTimer from './ChessTimer.vue';

export default {
    name: 'ChessBoardTopPanel',

    components: { ChessTimer },
};
</script>

<style lang="scss">
.chess-board-top-panel {
    width: 40rem;

    @apply flex h-12 items-center justify-between;

    p {
        @apply text-2xl;

        .chess-board-top-panel__opponent-name {
            @apply text-indigo-600;
        }
    }
}
</style>
