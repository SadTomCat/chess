<template>
    <div class="chess-chat">

        <!-- Top -->
        <div class="chess-chat__top">
            <h1>Chat</h1>
            <span class="material-icons chess-chat__icon-btn"
                  :class="{'text-red-600': store.state.game.chatMute}"
                  @click="store.commit('SWITCH_CHAT_MUTE')"
            >
                person_off
            </span>
        </div>

        <!-- Chat content -->
        <div class="chess-chat__messages">
            <chess-board-chat-message
                v-for="(message, index) in store.state.game.messages"
                :message="message.message"
                :fromOpponent="message.fromOpponent"
                :key="index"
            >
            </chess-board-chat-message>
        </div>

        <!-- Bottom -->
        <div class="chess-chat__input-block">
            <textarea rows="2" maxlength="255" v-model="message" :disabled="sending"></textarea>
            <span class="chess-chat__icon-btn material-icons" @click="sendMessage">send</span>
        </div>
    </div>
</template>

<script>
import { ref } from 'vue';
import { useRoute } from 'vue-router';
import { useStore } from 'vuex';
import ChessBoardChatMessage from './ChessBoardChatMessage.vue';
import sendGameMessage from '~/api/sendGameMessage';

export default {
    name: 'ChessBoardChat',

    setup() {
        const route = useRoute();
        const store = useStore();

        const gameToken = route.params.token;
        const message = ref('');
        const sending = ref(false);

        const sendMessage = async () => {
            if (message.value.length < 1 || message.value.length > 255 || sending.value) {
                return;
            }

            sending.value = true;

            const res = await sendGameMessage(gameToken, message.value);

            if (res.status === true) {
                store.commit('PUSH_MESSAGE', { message: message.value });
                message.value = '';
            }

            sending.value = false;
        };

        /* TODO: add mute */

        return {
            store,
            message,
            sending,
            sendMessage,
        };
    },

    components: { ChessBoardChatMessage },
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
