<template>
    <div class="base-table-settings-card">
        <slot></slot>
    </div>
</template>

<script>
export default {
    name: 'BaseTableSettingsCard',
};
</script>

<style lang="scss">
.base-table-settings-card {
    min-height: 4rem;
    min-width: 18rem;
    max-width: 24rem;

    @apply px-5 py-5 bg-white shadow rounded-2xl;

    input[type=checkbox]:checked {
        @apply bg-pink-600 hover:bg-pink-600 focus:bg-pink-600;
    }

    input {
        @apply mr-3 rounded focus:ring-0;
    }
}
</style>
