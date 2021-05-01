<template>
    <div class="admin h-full">
        <admin-side-panel></admin-side-panel>

        <div class="admin__content">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
import AdminSidePanel from '~/components/admin/AdminSidePanel.vue';

export default {
    name: 'Admin',

    components: { AdminSidePanel },
};
</script>

<style lang="scss">
.admin__content {
    margin-left: 18rem;
    background-color: #eff2ff;
    min-height: 100%;
    height: fit-content;
}
</style>
