<template>
    <div class="admin-games-view">
        <chess-base-view-game :game-info="gameInfo"
                              :winner-info="winnerInfo"
                              :loser-info="loserInfo"
                              :moves="moves"
                              :more-info-about-user-path="'/admin/view/users'"
                              :loading="loading"
        ></chess-base-view-game>
    </div>
</template>

<script>
import { useRoute } from 'vue-router';
import { onBeforeMount } from 'vue';
import ChessBaseViewGame from '../../../components/chess/ChessBaseViewGame.vue';
import adminGameInfoRequest from '../../../api/adminGameInfoRequest';
import viewGameHelper from '../../../helpers/viewGameHelper';
import ViewGameUserInfo from '../../../classes/viewGame/ViewGameUserInfo';
import ViewGameGameInfo from '../../../classes/viewGame/ViewGameGameInfo';

export default {
    name: 'AdminViewGame',

    setup() {
        const route = useRoute();

        const {
            loading,
            gameInfo,
            winnerInfo,
            loserInfo,
            moves,
            afterRequestAction,
        } = viewGameHelper(ViewGameGameInfo, ViewGameUserInfo);

        onBeforeMount(async () => {
            const data = await adminGameInfoRequest(route.params.id);

            if (data.status === false) {
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

.admin-games-view {
    @apply flex justify-between flex-wrap py-8 px-10 w-full text-xl;

    &__users-cards {
        width: 66.6%;
        @apply flex justify-between;
    }
}

</style>
