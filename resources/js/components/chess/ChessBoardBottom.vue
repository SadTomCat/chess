<template>
    <div class="chess-board-bottom py-5 text-xl">
        <p>Info: <span class="text-red-600">{{ store.state.game.moveErrorMessage }}</span></p>
    </div>
</template>

<script>
import { useStore } from 'vuex';

export default {
    name: 'ChessBoardBottom',

    setup: () => ({
        store: useStore(),
    }),
};
</script>
