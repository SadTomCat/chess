<template>
    <div class="warning-pop-up__wrapper">
        <div class="warning-pop-up">
            <div class="warning-pop-up__header">
                <h1>Warning</h1>
            </div>

            <div class="warning-pop-up__content">
                <slot></slot>
            </div>

            <div class="warning-pop-up__btn-menu">
                <button @click="$emit('closeWarningPopUp')">close</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'WarningPopUp',

    emits: ['closeWarningPopUp'],
};
</script>

<style lang="scss">
.warning-pop-up__wrapper {
    @apply fixed h-screen w-screen z-50 top-0;
    @apply flex justify-center items-center;
}

.warning-pop-up {
    width: 35rem;
    @apply flex flex-col justify-between;
    @apply bg-white shadow-2xl rounded-md;

    &__header {
        @apply py-3 bg-yellow-500 rounded-t-md ;
        @apply text-white text-center text-3xl;
    }

    &__content {
        @apply flex-grow px-3 py-5;
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
