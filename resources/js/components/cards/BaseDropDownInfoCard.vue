<template>
    <div class="base-drop-down-info-card" :style="{width, height: getHeight}" ref="test">
        <div class="base-drop-down-info-card__top" :style="{backgroundColor, color}">
            <p class="overflow-ellipsis">{{ title === '' ? 'Info' : title }}</p>

            <div class="base-drop-down-info-card__full-info-button" @click="showFull = !showFull">
                <span class="material-icons text-2xl" v-if="showFull">remove</span>
                <span class="material-icons" v-else>add</span>
            </div>
        </div>

        <div class="base-drop-down-info-card__bottom" v-if="showFull">
            <ul>
                <li v-for="(value, key) in info" :key="value">
                    <span>{{ key }}</span>: {{ value }}
                </li>
            </ul>

            <router-link class="self-center mt-7" :to="moreInfoConfig.link" v-if="needMoreInfoLink">
                {{ moreInfoConfig.title }}
            </router-link>
        </div>
    </div>
</template>

<script>
import { computed, ref } from 'vue';

export default {
    name: 'BaseDropDownInfoCard',

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

        moreInfoConfig: {
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
        const showFull = ref(false);

        const needMoreInfoLink = computed(() => (props.moreInfoConfig.off === false && showFull.value === true));
        const test = ref(null);

        const getHeight = computed(() => {
            if (showFull.value === false) {
                return '4rem';
            }

            return props.height === undefined ? '' : props.height;
        });

        return {
            test,
            showFull,
            needMoreInfoLink,
            getHeight,
        };
    },
};
</script>

<style lang="scss">
.base-drop-down-info-card {
    overflow: hidden;

    @apply flex flex-col rounded-2xl shadow relative text-xl flex-grow-0;

    &__top {
        height: 4rem;
        @apply flex items-center justify-between;
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
