<template>
    <div class="admin-base-info-card inline-block" :class="[getHeight]" :style="{width, height}">
        <div class="admin-base-info-card__top" :style="{backgroundColor, color }">
            <p class="overflow-ellipsis">{{ title === '' ? 'Info' : title }}</p>

            <div class="admin-base-info-card__full-info-button" @click="fullInfo = !fullInfo">
                <span class="material-icons text-2xl" v-if="fullInfo">remove</span>
                <span class="material-icons" v-else>add</span>
            </div>
        </div>

        <div class="admin-base-info-card__bottom" v-if="fullInfo">
            <ul>
                <li v-for="(value, key) in info">
                    <span>{{ key }}</span>: {{ value }}
                </li>
            </ul>

            <router-link class="self-center mt-7" :to="moreInfoLinkObj.link" v-if="needMoreInfoLink">
                {{ moreInfoLinkObj.title }}
            </router-link>
        </div>
    </div>
</template>

<script>
import { computed, ref } from 'vue';

export default {
    name: 'AdminUserInfoCard',

    props: {
        info: Object,
        title: String,
        loading: Boolean,
        backgroundColor: {
            type: String,
            default: () => ('#fff'),
        },
        color: {
            type: String,
            default: () => ('#111'),
        },
        width: {
            type: String,
            default: () => ('49%'),
        },
        height: String,
        moreInfoLinkObj: {
            type: Object,
            default: () => ({
                off: true,
                styles: {},
                title: 'More info',
                link: '#',
            }),
        },
    },

    setup(props) {
        const fullInfo = ref(false);

        const needMoreInfoLink = computed(() => (props.moreInfoLinkObj.off === false && fullInfo.value === true));

        const getHeight = computed(() => (fullInfo.value === true ? 'h-96' : 'max-h-16'));

        return {
            fullInfo,
            needMoreInfoLink,
            getHeight,
        };
    },
};
</script>

<style lang="scss">
.admin-base-info-card {
    overflow: hidden;

    @apply flex flex-col rounded-2xl shadow-2xl relative text-xl;

    &__top {
        @apply h-16 flex items-center justify-between;
    }

    &__top, &__bottom {
        @apply px-7 py-6;
    }

    &__bottom {
        @apply flex flex-col justify-between h-full bg-white text-gray-900 pt-4 pb-8;
    }

    ul {
        @apply space-y-3;
    }

    &__full-info-button {
        @apply text-xl cursor-pointer flex flex-col justify-center;
    }
}
</style>
