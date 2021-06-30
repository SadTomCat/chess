<template>
    <base-drop-down-info-card :info="info"
                              :title="title"
                              :background-color="getBackgroundColor"
                              :color="'#fff'"
                              :more-info-config="moreInfoConfig"
                              :width="width"
                              :height="height"
    ></base-drop-down-info-card>
</template>

<script>
import { computed, watchEffect } from 'vue';
import BaseDropDownInfoCard from './BaseDropDownInfoCard.vue';

export default {
    name: 'UserInfoCardInViewGames',

    props: {
        info: {
            type: Object,
            default: () => ({}),
        },
        title: String,
        isWinner: Boolean,
        loading: Boolean,
        width: String,
        height: String,
        moreInfoLink: [String, undefined],
        needWinnerAndLoseColor: Boolean,
    },

    setup(props) {
        const moreInfoConfig = {};

        const getBackgroundColor = computed(() => {
            if (props.needWinnerAndLoseColor !== true) {
                return '#111';
            }

            return props.isWinner === true ? '#2c8d53' : '#a12020';
        });

        watchEffect(() => {
            moreInfoConfig.off = props.moreInfoLink === undefined;
            moreInfoConfig.link = props.moreInfoLink;
            moreInfoConfig.title = 'more info';
        });

        return {
            moreInfoConfig,
            getBackgroundColor,
        };
    },

    components: { BaseDropDownInfoCard },
};
</script>
