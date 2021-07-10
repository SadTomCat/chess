<template>
    <div class="view-game" v-show="loading === false">
        <div class="view-game__top">
            <h1 class="view-game__title">Replay</h1>

            <router-link class="view-game__statistics-link" to="/statistics">My statistics</router-link>
        </div>

        <chess-base-view-game :game-info="gameInfo"
                              :winner-info="winnerInfo"
                              :loser-info="loserInfo"
                              :moves="moves"
                              :loading="loading"
        ></chess-base-view-game>
    </div>
</template>

<script>
import { useRoute, useRouter } from 'vue-router';
import { onBeforeMount } from 'vue';
import ChessBaseViewGame from '../components/chess/ChessBaseViewGame.vue';
import viewGameHelper from '../helpers/viewGameHelper';
import ViewGameUserInfo from '../classes/viewGame/ViewGameUserInfo';
import ViewGameGameInfo from '../classes/viewGame/ViewGameGameInfo';
import getGameInfoRequest from '../api/game/getGameInfoRequest';

export default {
    name: 'ViewGame',

    setup() {
        const route = useRoute();
        const router = useRouter();

        const {
            loading,
            gameInfo,
            winnerInfo,
            loserInfo,
            moves,
            afterRequestAction,
        } = viewGameHelper(ViewGameGameInfo, ViewGameUserInfo);

        onBeforeMount(async () => {
            const data = await getGameInfoRequest(route.params.gameId);

            if (data.status === false) {
                if (data.unauthorized === true) {
                    await router.replace('/');
                }

                return;
            }

            afterRequestAction(data);
        });

        return {
            loading,
            gameInfo,
            winnerInfo,
            loserInfo,
            moves,
        };
    },

    components: {
        ChessBaseViewGame,
    },
};
</script>

<style lang="scss">

.view-game {
    @apply py-16 px-32;

    &__top {
        @apply flex justify-between;
    }

    &__title {
        @apply text-4xl mb-10;
    }

    &__statistics-link {
        @apply text-2xl;
    }
}
</style>
