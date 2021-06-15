<template>
    <div class="base-input-wrapper">
        <slot></slot>
    </div>
</template>

<script>
export default {
    name: 'BaseInputWrapper',
};
</script>

<style lang="scss">
.base-input-wrapper {
    @apply w-full;

    input {
        @apply w-full mt-1;
        @apply rounded-md shadow-sm border-gray-300;
        @apply focus:ring-1 focus:ring-yellow-600 focus:border-yellow-600 focus:ring-opacity-75;
    }

    label {
        @apply block font-medium text-sm text-gray-700;
    }
}

</style>
