<template>
    <div class="base-table-settings-card" :style="[heightStyles]">
        <div class="base-table-settings-card__header" @click="showFull = !showFull">
            <h1 class="">{{ title }}</h1>
            <span class="material-icons">expand_more</span>
        </div>

        <slot v-if="showFull === true"></slot>
    </div>
</template>

<script>
import { computed, ref } from 'vue';

export default {
    name: 'BaseTableSettingsCard',

    props: {
        title: {
            type: String,
            required: true,
        },
    },

    setup() {
        const showFull = ref(false);

        const heightStyles = computed(() => (showFull.value === false
            ? { height: '4rem' }
            : { minHeight: '4rem' }));

        return {
            showFull,
            heightStyles,
        };
    },
};
</script>

<style lang="scss">
.base-table-settings-card {
    min-width: 18rem;
    max-width: 24rem;

    @apply px-5 py-5 bg-white shadow rounded-2xl;

    &__header {
        @apply flex justify-between cursor-pointer;

        h1 {
            @apply text-xl align-middle;
        }

        span {
            margin-top: 2px;
        }
    }

    input[type=checkbox]:checked {
        @apply bg-pink-600 hover:bg-pink-600 focus:bg-pink-600;
    }

    input {
        @apply mr-3 rounded focus:ring-0;
    }
}
</style>
