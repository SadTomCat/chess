<template>
    <div class="chess-table-top-panel">
        <p>
            <span class="chess-table-top-panel__opponent-name">Opponent name:</span>
            {{ opponentName }}
        </p>

        <chess-timer :moveNum="moveNum" @timeEnded="$emit('timeEnded')"></chess-timer>
    </div>
</template>

<script>
import ChessTimer from './ChessTimer.vue';

export default {
    name: 'ChessTableTopPanel',

    props: {
        opponentName: String,
        moveNum: Number,
    },

    components: { ChessTimer },
};
</script>

<style lang="scss">
.chess-table-top-panel {
    width: 40rem;

    @apply flex h-12 items-center justify-between;

    p {
        @apply text-2xl;

        .chess-table-top-panel__opponent-name {
            @apply text-indigo-600;
        }
    }
}
</style>
