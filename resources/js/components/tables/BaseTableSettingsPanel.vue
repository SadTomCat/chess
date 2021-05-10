<template>
   <div class="base-table-settings-panel">
       <slot></slot>
   </div>
</template>

<script>
export default {
    name: 'BaseTableSettingsPanel',
};
</script>

<style lang="scss">
.base-table-settings-panel {
    @apply flex justify-between flex-wrap space-y-4;
}
</style>
