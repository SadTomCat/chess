<template>
    <div class="win-or-lose-pop-up__wrapper">
        <div class="win-or-lose-pop-up">
            <div class="win-or-lose-pop-up__header" :class="isWin ? 'bg-green-600' : 'bg-red-600'">
                <h1>{{ isWin ? 'You win' : 'You lose' }}</h1>
            </div>

            <div class="win-or-lose-pop-up__content">
                <p v-if="isWin">
                    Congratulations, you were able to win this difficult game.
                    Keep playing as you become the best player.
                    Oh yeah, take me to work.
                </p>

                <p v-else>
                    Unfortunately you lost, but you were able to gain a rewarding experience.
                    Keep playing and you can become the best in this game.
                    Oh yeah, take me to work.
                </p>
            </div>

            <div class="win-or-lose-pop-up__btn-menu">
                <button @click="close">close</button>
            </div>
        </div>
    </div>
</template>

<script>
import { useRouter } from 'vue-router';

export default {
    name: 'ChessWinOrLosePopUp',

    props: {
        isWin: Boolean,
    },

    setup() {
        const router = useRouter();

        return {
            close: () => {
                router.replace('/');
            },
        };
    },
};
</script>

<style lang="scss">
.win-or-lose-pop-up__wrapper {
    background: rgba(1, 1, 1, 0.2);

    @apply fixed h-screen w-screen z-50 top-0;
    @apply flex justify-center items-center;
}

.win-or-lose-pop-up {
    width: 45%;
    @apply flex flex-col justify-between;
    @apply bg-white shadow-2xl rounded-md;

    &__header {
        @apply py-5 rounded-t-md ;
        @apply text-white text-center text-3xl;
    }

    &__content {
        @apply flex-grow px-9 py-5;

        p {
            @apply text-2xl;
        }
    }

    &__btn-menu {
        @apply flex justify-end py-4 px-7;

        button {
            @apply bg-yellow-500 text-white py-3 px-9;
            @apply shadow-md rounded-full focus:outline-none;
        }
    }
}
</style>
