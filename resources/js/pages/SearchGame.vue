<template>
    <div class="search-game-wrapper">

        <!-- Authorized user -->
        <div class="search-game__authorized" v-if="canSearch">
            <h1 class="search-game__title">Do You wanna play?</h1>
            <button :disabled="disableSearchBtn" @click="searchGameHandle" v-if="!searching">Search game</button>
            <button @click="cancelHandle" v-else>cancel</button>
        </div>

        <!-- If user unauthorized -->
        <div class="search-game__unauthorized" v-else>
            <h1 class="search-game__title">Login for search game</h1>
        </div>

        <!--  Error  -->
        <teleport to="body">
            <error-pop-up @closeErrorPopUp="closeErrorPopUp" v-if="errorPopUpOpen">
                <search-game-error :reasons="errorReasons" :solutions="errorSolutions"></search-game-error>
            </error-pop-up>
        </teleport>
    </div>
</template>

<script>
import {
    computed, ref, onBeforeUnmount, reactive,
} from 'vue';
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';
import ErrorPopUp from '~/components/ErrorPopUp.vue';
import SearchGameError from '~/components/errors/SearchGameError.vue';

export default {
    name: 'SearchGame',

    setup() {
        const store = useStore();
        const router = useRouter();

        const canSearch = computed(() => store.state.user.logged);
        const searching = ref(false);
        const disableSearchBtn = ref(false);

        /* Error pop up */

        const errorPopUpOpen = ref(false);

        const errorReasons = reactive([]);

        const errorSolutions = reactive([]);

        const closeErrorPopUp = () => {
            errorReasons.length = 0;
            errorSolutions.length = 0;
            errorPopUpOpen.value = false;
        };

        /* Search game */

        const searchGameHandle = () => {
            disableSearchBtn.value = true;

            window.echo.join(`search-game-${store.state.user.id}`)
                .subscribed(async () => {
                    searching.value = true;
                    disableSearchBtn.value = false;
                    await window.axios.post('/subscribed/search-game');
                })
                .listen('GameFoundEvent', (data) => {
                    window.echo.leave(`search-game-${store.state.user.id}`);
                    router.replace(`/game/${data.gameToken}`);
                })
                .error((e) => {
                    errorPopUpOpen.value = true;
                    window.echo.leave(`search-game-${store.state.user.id}`);

                    if (e.status === 403) {
                        errorReasons.push('You logout', 'You are searching game', 'You in the game');
                        errorSolutions.push('Reload page', 'Check other page', 'Click on search game again');
                    }

                    disableSearchBtn.value = false;
                });
        };

        const cancelHandle = () => {
            window.echo.leave(`search-game-${store.state.user.id}`);
            searching.value = false;
        };

        /* Hooks */
        onBeforeUnmount(() => {
            window.echo.leave(`search-game-${store.state.user.id}`);
        });

        return {
            canSearch,
            searching,
            disableSearchBtn,
            searchGameHandle,
            cancelHandle,
            errorPopUpOpen,
            errorReasons,
            errorSolutions,
            closeErrorPopUp,
        };
    },

    components: {
        ErrorPopUp,
        SearchGameError,
    },
};
</script>

<style lang="scss">
.search-game-wrapper {
    @apply flex justify-center items-center h-full pb-24 w-full;
}

.search-game {
    &__title {
        @apply text-4xl text-center;
    }

    &__authorized {
        @apply flex flex-col justify-center items-center space-y-8 w-full;

        button {
            @apply py-3 px-6;
            @apply text-white text-xl shadow-md rounded-full focus:outline-none;
            @apply bg-gradient-to-r from-yellow-500 to-pink-500;

            &:hover {
                @apply bg-gradient-to-br from-yellow-500 to-pink-500;
            }
        }
    }
}
</style>
