<template>
    <div class="chess-chat__message" :class="{'chess-chat__opponent-message': !fromOpponent}">
        <p>{{ message }}</p>
    </div>
</template>

<script>
export default {
    name: 'ChessTableChatMessage',

    props: {
        message: String,
        fromOpponent: Boolean,
    },
};
</script>

<style lang="scss">
.chess-chat__message {
    @apply py-1 px-2;
    @apply rounded-md;
    width: 90%;
    word-wrap: anywhere;

    @apply bg-indigo-700 text-white;
}

.chess-chat__opponent-message {
    @apply self-end text-black shadow-md bg-gray-200;
}
</style>
