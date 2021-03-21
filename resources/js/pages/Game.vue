<template>
    <div class="chess-game-wrapper">

        <div class="chess-game">

            <!-- Top -->
            <chess-table-top-panel :opponent-name="'test'"></chess-table-top-panel>

            <div class="chess-game__game-and-chat">
                <!-- Chess table -->
                <chess-table></chess-table>

                <!-- Chat -->
                <chess-table-chat></chess-table-chat>
            </div>

        </div>

    </div>
</template>

<script>
import ChessTable from '../components/ChessTable.vue';
import ChessTableChat from '../components/ChessTableChat.vue';
import ChessTableTopPanel from '../components/ChessTableTopPanel.vue';

export default {
    name: 'Game',

    components: { ChessTable, ChessTableChat, ChessTableTopPanel },
};
</script>

<style lang="scss">
.chess-game-wrapper {
    @apply  flex justify-center py-24 px-10;
}

.chess-game {
    @apply flex flex-col;

    &__game-and-chat {
        @apply flex;
    }
}
</style>
