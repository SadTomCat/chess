<template>
  <div class="chess-base-view-game">
    <base-drop-down-info-card :info="gameInfo.getFormattedInfo()"
                              :title="'Game info'"
                              :loading="loading"
                              :width="'31.5%'"
                              :background-color="'#111'"
                              :color="'#fff'"
    ></base-drop-down-info-card>

    <div class="chess-base-view-game__users-cards">

      <user-info-card-in-view-games :info="winnerInfo.getFormattedInfo()"
                                    :title="getUserCardTitle(winnerInfo)"
                                    :loading="loading"
                                    :need-winner-and-lose-color="gameInfo.winnerExists()"
                                    :is-winner="true"
                                    :more-info-link="getMoreInfoAboutUserLink(winnerInfo)"
      ></user-info-card-in-view-games>

      <user-info-card-in-view-games :info="loserInfo.getFormattedInfo()"
                                    :title="getUserCardTitle(loserInfo)"
                                    :loading="loading"
                                    :need-winner-and-lose-color="gameInfo.winnerExists()"
                                    :is-winner="false"
                                    :more-info-link="getMoreInfoAboutUserLink(loserInfo)"
      ></user-info-card-in-view-games>

    </div>

    <chess-game-replay class="mt-10" :moves="moves"></chess-game-replay>
  </div>
</template>

<script>
import ChessGameReplay from './ChessGameReplay.vue';
import BaseDropDownInfoCard from '../cards/BaseDropDownInfoCard.vue';
import UserInfoCardInViewGames from '../cards/UserInfoCardInViewGames.vue';
import stringHelper from '../../helpers/stringHelper';
import ViewGameUserInfo from '../../classes/viewGame/ViewGameUserInfo';
import ViewGameGameInfo from '../../classes/viewGame/ViewGameGameInfo';

export default {
    name: 'ChessBaseViewGame',

    props: {
        gameInfo: {
            type: ViewGameGameInfo,
            required: true,
        },

        winnerInfo: {
            type: ViewGameUserInfo,
            required: true,
        },

        loserInfo: {
            type: ViewGameUserInfo,
            required: true,
        },

        moves: {
            type: Array,
            required: true,
        },

        moreInfoAboutUserPath: String,

        loading: {
            type: Boolean,
            default: () => false,
        },
    },

    setup(props) {
        const { upperFirstLetter } = stringHelper();

        /** @param {ViewGameUserInfo} info */
        const getUserCardTitle = (info) => `Name: ${upperFirstLetter(info.getUserName())}`;

        /** @param {ViewGameUserInfo} info */
        const getMoreInfoAboutUserLink = (info) => ((props.moreInfoAboutUserPath !== undefined)
            ? `${props.moreInfoAboutUserPath}/${info.getUserId()}`
            : undefined
        );

        return {
            getUserCardTitle,
            getMoreInfoAboutUserLink,
        };
    },

    components: {
        UserInfoCardInViewGames,
        BaseDropDownInfoCard,
        ChessGameReplay,
    },
};
</script>

<style lang="scss">
.chess-base-view-game {
  @apply flex justify-between flex-wrap w-full text-xl items-start;

  &__users-cards {
    width: 66.6%;
    @apply flex justify-between items-start;
  }
}
</style>
