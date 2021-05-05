<template>
    <admin-base-info-card :info="formattedInfo"
                          :title="title"
                          :background-color="getBackgroundColor"
                          :color="'#fff'"
                          :more-info-link-obj="moreInfoLinkObj"
                          :width="width"
                          :height="height"
    ></admin-base-info-card>
</template>

<script>
import { computed, ref, watchEffect } from 'vue';
import AdminBaseInfoCard from './AdminBaseInfoCard.vue';
import stringHelper from '~/helpers/stringHelper';

export default {
    name: 'AdminUserInfoCard',

    props: {
        info: {
            type: Object,
            default: () => ({}),
        },
        needWinnerColor: Boolean,
        isWinner: Boolean,
        loading: Boolean,
        width: String,
        height: String,
    },

    setup(props) {
        const title = ref('');
        const formattedInfo = {};
        const moreInfoLinkObj = {};
        const { upperFirstLetter } = stringHelper();

        const getBackgroundColor = computed(() => {
            if (props.needWinnerColor === false) {
                return '#111';
            }

            return props.isWinner === true ? '#2c8d53' : '#a12020';
        });

        watchEffect(() => {
            moreInfoLinkObj.off = props.info.id === undefined;
            moreInfoLinkObj.link = `/admin/view/users/${props.info.id}`;
            moreInfoLinkObj.title = 'more info';

            Object.getOwnPropertyNames(props.info).forEach((el) => {
                if (el === 'name') {
                    title.value = `Name: ${props.info[el]}`;
                    return;
                }

                formattedInfo[upperFirstLetter(el.replace(/_/g, ' '))] = props.info[el];

                if (el === 'count_won') {
                    const countLose = props.info.count_games - props.info.count_won;
                    formattedInfo['Win rate'] = (props.info.count_won / countLose).toFixed(2);
                }
            });
        });

        return {
            title,
            formattedInfo,
            moreInfoLinkObj,
            getBackgroundColor,
        };
    },

    components: { AdminBaseInfoCard },
};
</script>

<style lang="scss">

</style>
