<template>
    <div class="admin h-full">
        <admin-side-panel></admin-side-panel>

        <div class="admin__content">
            <h1 class="admin__header">{{ getPageName }}</h1>
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
import { useRoute } from 'vue-router';
import { computed } from 'vue';
import AdminSidePanel from '~/components/admin/AdminSidePanel.vue';
import stringHelper from '~/helpers/stringHelper';

export default {
    name: 'Admin',

    components: { AdminSidePanel },

    setup() {
        const route = useRoute();
        const { upperFirstLetter } = stringHelper();

        const clearPath = (str) => str.replace('/admin/', '').replace(/-/g, ' ').replace('/', '');

        const getPageName = computed(() => (route.path.match(/\/admin?\/$/)
            ? 'Admin'
            : upperFirstLetter(clearPath(route.path))));

        return {
            upperFirstLetter,
            getPageName,
        };
    },
};
</script>

<style lang="scss">
.admin__content {
    margin-left: 18rem;
    background-color: #eff2ff;
    min-height: 100%;
    height: fit-content;
}

.admin__header {
    @apply text-3xl py-3 pl-10 bg-gray-300;
}
</style>
