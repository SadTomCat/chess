<template>
    <div class="chess-chat">

        <!-- Top -->
        <div class="chess-chat__top">
            <h1>Chat</h1>
            <span class="material-icons chess-chat__icon-btn">person_off</span>
        </div>

        <!-- Chat content -->
        <div class="chess-chat__messages">
            <chess-table-chat-message
                v-for="(message, index) in messages"
                :message="message.message"
                :fromOpponent="message.formOpponent"
                :key="index"
            >
            </chess-table-chat-message>
        </div>

        <!-- Bottom -->
        <div class="chess-chat__input-block">
            <textarea rows="2" maxlength="100"></textarea>
            <span class="chess-chat__icon-btn material-icons">send</span>
        </div>
    </div>
</template>

<script>
import { reactive } from 'vue';
import ChessTableChatMessage from './ChessTableChatMessage.vue';

export default {
    name: 'ChessTableChat',

    props: {
        opponentName: String,
    },

    setup() {
        const messages = reactive([
            {
                message: 'test',
            },
            {
                message: 'test',
                formOpponent: true,
            },
            {
                message: 'test',
            },
            {
                message: 'test',
            },
            {
                message: 'test',
                formOpponent: true,
            },
            {
                message: 'test',
                formOpponent: true,
            },
            {
                message: 'test',
            },
        ]);

        return { messages };
    },

    components: { ChessTableChatMessage },
};
</script>

<style lang="scss">
.chess-chat {
    height: 40rem;
    width: 20rem;
    @apply flex flex-col;
    @apply border-b border-t border-r border-gray-300;

    &__icon-btn {
        @apply cursor-pointer;
    }

    &__top {
        @apply flex justify-between items-center px-2 text-gray-900;

        h1 {
            @apply text-2xl;
        }
    }

    &__messages {
        @apply flex flex-col justify-end h-full px-2 py-4 space-y-3;
        @apply border-b border-t border-gray-300;
        overflow-y: scroll;

        &::-webkit-scrollbar {
            @apply w-2;
        }

        &::-webkit-scrollbar-thumb {
            @apply bg-indigo-600 rounded-md;
        }
    }

    &__input-block {
        @apply flex items-center pr-1;

        textarea {
            @apply w-full resize-none border-none focus:outline-none focus:ring-0 ;
        }

        textarea::-webkit-scrollbar {
            width: 0;
        }
    }
}
</style>
