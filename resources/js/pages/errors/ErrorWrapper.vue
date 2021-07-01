<template>
    <div class="error-wrapper">

        <h1 class="error-wrapper__title">{{ title }}</h1>

        <p class="error-wrapper__message">{{ message }}</p>

        <router-link class="error-wrapper__main-page-link" to="/">main page</router-link>

    </div>
</template>

<script>
export default {
    name: 'ErrorWrapper',

    props: {
        title: {
            type: String,
            default: () => 'Error',
            required: true,
        },
        message: String,
    },
};
</script>

<style lang="scss">
.error-wrapper {
    @apply flex flex-col justify-center items-center h-screen w-full;

    &__title {
        @apply text-5xl text-gray-600  font-light mb-5;
    }

    &__message {
        @apply text-xl text-gray-500 mb-8;
    }

    &__main-page-link {
        @apply text-xl;
    }
}
</style>
