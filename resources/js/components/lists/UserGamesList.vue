<template>
    <div class="user-games-list">

        <div class="user-games-list__top">
            <h1>Last games</h1>

            <span class="material-icons user-games-list__open-btn" @click="switchOpenStatus" v-if="isOpen === true">
                remove
            </span>
            <span class="material-icons user-games-list__open-btn" @click="switchOpenStatus" v-else>add</span>
        </div>

        <div class="user-games-list__bottom" v-if="isOpen === true">
            <ul>
                <li v-for="game in games" :key="game.token">

                    <div class="user-games-list__about-game">

                        <span class="user-games-list__game-symbol" :class="getGameColor(game)">
                            {{ getGameSymbol(game) }}
                        </span>{{ game.token }}

                    </div>

                    <router-link class="user-games-list__more-info"
                                 :to="`${moreInfoRoute}/${game.id}`"
                                 v-if="moreInfoRoute.length > 0"
                    >more info</router-link>

                </li>
            </ul>

            <base-pagination :current-page="currentPage"
                             :total-pages="totalPages"
                             @newPageAction="newPageAction"
            ></base-pagination>
        </div>

    </div>
</template>

<script>
import {
    computed, onBeforeMount, reactive, ref,
} from 'vue';
import BasePagination from '~/components/BasePagination.vue';
import paginatedUserGamesRequest from '~/api/paginatedUserGamesRequest';

export default {
    name: 'UserGamesList',

    props: {
        userId: Number,

        moreInfoRoute: {
            type: String,
            default: () => '',
        },
    },

    setup(props) {
        const loading = ref(true);
        const isUserOpen = ref(false);

        const isOpen = computed(() => loading.value === false && isUserOpen.value === true);

        const switchOpenStatus = () => {
            isUserOpen.value = !isUserOpen.value;
        };

        const games = reactive([]);
        const currentPage = ref(1);
        const totalPages = ref(0);

        const newPageAction = async (newPage) => {
            const res = await paginatedUserGamesRequest(props.userId, newPage);

            if (res.status === false) {
                console.log('Cannot get items from games');
                return false;
            }

            games.length = 0;
            totalPages.value = res.last_page;
            currentPage.value = newPage;
            res.items.forEach((el) => {
                games.push(el);
            });

            return true;
        };

        const getGameColor = (game) => (game.winner_color === game.color ? 'text-green-600' : 'text-red-700');

        const getGameSymbol = (game) => {
            if (game.winner_color === game.color) {
                return 'W';
            }

            return game.end_at !== null ? 'L' : '?';
        };

        onBeforeMount(async () => {
            await newPageAction(1);
            loading.value = false;
        });

        return {
            games,
            currentPage,
            totalPages,
            newPageAction,
            getGameColor,
            getGameSymbol,
            loading,
            isOpen,
            switchOpenStatus,
        };
    },

    components: { BasePagination },
};
</script>

<style lang="scss">
.user-games-list {
    @apply bg-white rounded-2xl pt-8 pb-8 px-7 w-full shadow space-y-10;

    &__top {
        @apply flex justify-between items-center align-middle;
    }

    h1 {
        @apply text-3xl;
    }

    &__open-btn {
        @apply text-3xl cursor-pointer;
    }

    &__bottom {
        @apply space-y-10;
    }

    ul {
        @apply text-xl space-y-9;

        li {
            @apply flex pb-1 justify-between border-b;
        }
    }

    &__game-symbol {
        @apply mr-4;
    }

    &__more-info {
        @apply justify-end self-end;
    }
}
</style>
