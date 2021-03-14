<template>
    <div class="chess-table-top-panel">
        <p>
            <span class="chess-table-top-panel__opponent-name">Opponent name:</span>
            {{ opponentName }}
        </p>
    </div>
</template>

<script>
export default {
    name: 'ChessTableTopPanel',

    props: {
        opponentName: String,
    },
};
</script>

<style lang="scss">
.chess-table-top-panel {
    @apply w-full h-12;

    p {
        @apply text-2xl;

        .chess-table-top-panel__opponent-name {
            @apply text-indigo-600;
        }
    }
}
</style>
