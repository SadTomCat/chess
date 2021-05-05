<template>
    <div class="admin-games-view">
        <admin-base-info-card :info="gameInfo"
                              :title="'Game info'"
                              :loading="loading"
                              :height="'26rem'"
                              :width="'31.5%'"
                              :background-color="'#111'"
                              :color="'#fff'"
        ></admin-base-info-card>

        <div class="admin-games-view__users-cards">

            <admin-user-info-card-in-games :info="winnerInfo"
                                           :loading="loading"
                                           :is-winner="true"
                                           :need-winner-color="needWinnerColor"
                                           :height="'26rem'"
            ></admin-user-info-card-in-games>

            <admin-user-info-card-in-games :info="loserInfo"
                                           :loading="loading"
                                           :is-winner="false"
                                           :need-winner-color="needWinnerColor"
                                           :height="'26rem'"
            ></admin-user-info-card-in-games>

        </div>

        <chess-game-replay class="mt-10" :loading="loading" :moves="moves"></chess-game-replay>
    </div>
</template>

<script>
import { useRoute } from 'vue-router';
import { onBeforeMount, reactive, ref } from 'vue';
import AdminBaseInfoCard from '~/components/admin/AdminBaseInfoCard.vue';
import AdminUserInfoCardInGames from '~/components/admin/AdminUserInfoCardInGames.vue';
import ChessGameReplay from '~/components/chess/ChessGameReplay.vue';
import adminGamesViewRequest from '~/api/adminGameInfoRequest';
import stringHelper from '~/helpers/stringHelper';

export default {
    name: 'AdminGamesView',

    setup() {
        const route = useRoute();
        const { upperFirstLetter } = stringHelper();

        const loading = ref(true);

        const gameInfo = reactive({});
        const winnerInfo = reactive({});
        const loserInfo = reactive({});
        const needWinnerColor = ref(false);
        const moves = reactive([]);

        const setUsersInfo = (data) => {
            const winnerColor = data.game.winner_color ?? 'white';
            const loserColor = winnerColor === 'white' ? 'black' : 'white';

            Object.getOwnPropertyNames(data.users[winnerColor])
                .forEach((el) => {
                    winnerInfo[el] = data.users[winnerColor][el];
                });
            winnerInfo.Color = winnerColor;

            Object.getOwnPropertyNames(data.users[loserColor])
                .forEach((el) => {
                    loserInfo[el] = data.users[loserColor][el];
                });
            loserInfo.Color = loserColor;
        };

        const setGameInfo = (data) => {
            Object.getOwnPropertyNames(data.game)
                .forEach((el) => {
                    gameInfo[upperFirstLetter(el.replace(/_/g, ' '))] = data.game[el];

                    if (el === 'end_at' && data.game[el] !== null) {
                        const startAt = (new Date(data.game.start_at)).getTime() / 1000;
                        const endAt = (new Date(data.game.end_at)).getTime() / 1000;

                        const minAndSec = (endAt - startAt) / 60;
                        gameInfo.Duration = `${Math.floor(minAndSec)}m : ${(endAt - startAt) % 60}s`;
                    }

                    if (el === 'winner_color' && data.game[el] !== null) {
                        needWinnerColor.value = true;
                        gameInfo['Winner name'] = data.users[data.game[el]].name;
                        gameInfo['Count move'] = data.moves.length;
                    }
                });
        };

        onBeforeMount(async () => {
            const data = await adminGamesViewRequest(route.params.id);

            if (data.status === false) {
                return;
            }

            setUsersInfo(data);
            setGameInfo(data);
            data.moves.forEach((el) => {
                moves.push(el);
            });

            loading.value = false;
        });

        return {
            loading,
            gameInfo,
            winnerInfo,
            loserInfo,
            needWinnerColor,
            moves,
        };
    },

    components: {
        ChessGameReplay,
        AdminBaseInfoCard,
        AdminUserInfoCardInGames,
    },
};
</script>

<style lang="scss">

.admin-games-view {
    @apply flex justify-between flex-wrap py-8 px-10 w-full text-xl;

    &__users-cards {
        width: 66.6%;
        @apply flex justify-between;
    }
}

</style>
