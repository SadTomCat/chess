<template>
    <div class="search-game-wrapper">
        <!-- Authorized user -->
        <div class="search-game__authorized" v-if="canSearch">
            <h1 class="search-game__title">Do You wanna play?</h1>
            <button @click="searchGameHandle" v-if="!searching">Search game</button>
            <button @click="cancelHandle" v-else>cancel</button>
        </div>

        <!-- If user unauthorized -->
        <div class="search-game__unauthorized" v-else>
            <h1 class="search-game__title">Login for search game</h1>
        </div>
    </div>
</template>

<script>
import { computed, ref } from 'vue';
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';

export default {
    name: 'SearchGame',

    setup() {
        const store = useStore();

        const router = useRouter();

        const canSearch = computed(() => store.state.user.logged);

        const searching = ref(false);

        const searchGameHandle = () => {
            echo.join(`search-game-${store.state.user.id}`)
                .subscribed(async () => {
                    searching.value = true;
                    await axios.post('/subscribed/search-game');
                })
                .listen('JoinToGameEvent', (data) => {
                    echo.leave(`search-game-${store.state.user.id}`);
                    router.replace(`/game/${data.gameToken}`);
                })
                .error((e) => {
                    console.log(e);
                });
        };

        const cancelHandle = () => {
            searching.value = false;
            echo.leave(`search-game-${store.state.user.id}`);
        };

        return {
            canSearch,
            searching,
            searchGameHandle,
            cancelHandle,
        };
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
