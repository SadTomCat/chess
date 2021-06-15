<template>
    <div class="base-informer-block__base base-informer-block__successful"
         v-if="showSuccessful"
    >
        <span class="material-icons">done</span>
        <p>{{ successful }}</p>
    </div>

    <div class="base-informer-block__base base-informer-block__notice"
         v-if="showNotice"
    >
        <span class="material-icons">info</span>
        <p>{{ notice }}</p>
    </div>

    <div class="base-informer-block__base base-informer-block__error"
         v-if="showError"
    >
        <span class="material-icons">cancel</span>
        <p>{{ error }}</p>
    </div>
</template>

<script>
import { computed } from 'vue';

export default {
    name: 'BaseInformerBlock',

    props: {
        successful: {
            type: String,
            default: () => '',
        },
        notice: {
            type: String,
            default: () => '',
        },
        error: {
            type: String,
            default: () => '',
        },
    },

    setup(props) {
        const showSuccessful = computed(() => props.successful !== '' && props.notice === '' && props.error === '');

        const showNotice = computed(() => props.notice !== '' && props.successful === '' && props.error === '');

        const showError = computed(() => props.error !== '' && props.successful === '' && props.notice === '');

        return {
            showSuccessful,
            showNotice,
            showError,
        };
    },
};
</script>

<style lang="scss">
.base-informer-block {
    &__base {
        @apply flex justify-between items-center w-full h-14;
        @apply mb-6 px-5 rounded-xl text-white overflow-x-hidden overflow-y-hidden;

        p {
            @apply text-2xl w-full ml-4;
        }

        span {
            @apply text-3xl;
        }
    }

    &__successful {
        @apply bg-green-600;
    }

    &__notice {
        @apply bg-yellow-600;
    }

    &__error {
        @apply bg-red-600;
    }
}
</style>
