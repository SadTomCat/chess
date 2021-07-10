<template>
    <div class="statistics-page">
        <div class="statistics-page__statistics-block">
            <h1 class="statistics-page__title">Statistics</h1>

            <ul class="statistics-page__statistics">
                <li v-for="statistic in statistics" :key="statistic.name">
                    <span>{{ statistic.name }}</span> : <span>{{ statistic.value }}</span>
                </li>
            </ul>
        </div>

        <user-games-list :user-id="user.id" :more-info-route="'/view/games'"></user-games-list>
    </div>
</template>

<script>
import { useStore } from 'vuex';
import { onBeforeMount, reactive } from 'vue';
import UserGamesList from '../components/lists/UserGamesList.vue';
import getUserGamesStatisticsRequest from '../api/users/getUserGamesStatisticsRequest';
import gamesStatisticsHelper from '../helpers/gamesStatisticsHelper';

export default {
    name: 'Statistics',

    setup() {
        const store = useStore();
        const { getWinRateAndLoseGames } = gamesStatisticsHelper();

        const { user } = store.state;
        const statistics = reactive({
            countGames: {
                name: 'Count games',
                value: '',
            },
            countWon: {
                name: 'Count won',
                value: '',
            },
            loseGames: {
                name: 'Count lose games',
                value: '',
            },
            notCountGames: {
                name: 'Not count games',
                value: '',
            },
            winRate: {
                name: 'Win rate',
                value: '',
            },
        });

        onBeforeMount(async () => {
            const data = await getUserGamesStatisticsRequest(user.id);

            if (data.status === false) {
                console.log(data.message);
                return;
            }

            statistics.countGames.value = data.statistics.count_games;
            statistics.countWon.value = data.statistics.count_won;
            statistics.notCountGames.value = data.statistics.not_count_games;

            const test = getWinRateAndLoseGames(
                data.statistics.count_games,
                data.statistics.count_won,
                data.statistics.not_count_games,
            );

            statistics.loseGames.value = `${test.loseGames}`;
            statistics.winRate.value = `${test.winRate}`;
        });

        return {
            user,
            statistics,
        };
    },

    components: { UserGamesList },
};
</script>

<style lang="scss">
.statistics-page {
    @apply min-h-full py-10 px-32;

    &__statistics-block {
        @apply bg-white rounded-2xl px-8 py-8 shadow mb-10;
    }

    &__title {
        @apply text-4xl mb-5;
    }

    &__statistics {
        @apply text-2xl space-y-5;

        li {
            @apply border-b;
        }
    }
}
</style>
