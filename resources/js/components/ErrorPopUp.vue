<template>
    <div class="error-pop-up__wrapper">
        <div class="error-pop-up">
            <div class="error-pop-up__header">
                <h1>Error</h1>
            </div>

            <div class="error-pop-up__content">
                <slot></slot>
            </div>

            <div class="error-pop-up__btn-menu">
                <button @click="$emit('closeErrorPopUp')">close</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'ErrorPopUp',

    setup() {
    },
};
</script>

<style lang="scss">
.error-pop-up__wrapper {
    background: rgba(1, 1, 1, 0.2);

    @apply fixed h-screen w-screen z-50 top-0;
    @apply flex justify-center items-center;
}

.error-pop-up {
    min-height: 40%;
    width: 45%;
    @apply flex flex-col justify-between;
    @apply bg-white shadow-2xl rounded-md;

    &__header {
        @apply py-5 bg-red-600 rounded-t-md ;
        @apply text-white text-center text-3xl;
    }

    &__content {
        @apply flex-grow px-9 py-5;
    }

    &__btn-menu {
        @apply flex justify-end py-4 px-7;

        button {
            @apply bg-yellow-500 text-white py-3 px-9;
            @apply shadow-md rounded-full focus:outline-none;
        }
    }
}
</style>
