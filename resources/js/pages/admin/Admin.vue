<template>
    <div class="admin h-full">
        <admin-side-panel></admin-side-panel>

        <div class="admin__content">
            <div class="admin__header-block">
                <span class="material-icons" @click="router.back()">arrow_back_ios</span>
                <h1>{{ getPageName }}</h1>
            </div>
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
import { useRoute, useRouter } from 'vue-router';
import { computed } from 'vue';
import AdminSidePanel from '~/components/admin/AdminSidePanel.vue';
import stringHelper from '~/helpers/stringHelper';

export default {
    name: 'Admin',

    components: { AdminSidePanel },

    setup() {
        const route = useRoute();
        const router = useRouter();

        const { upperFirstLetter } = stringHelper();

        const clearPath = (str) => str
            .replace('/admin/', '')
            .replace(/-/g, ' ')
            .replace(/\//g, ' ')
            .replace(/[0-9]/g, '');

        const getPageName = computed(() => (route.path.match(/\/admin\/?$/)
            ? 'Admin panel'
            : upperFirstLetter(clearPath(route.path))));

        return {
            router,
            upperFirstLetter,
            getPageName,
        };
    },
};
</script>

<style lang="scss">
.admin__content {
    margin-left: 18rem;
    background-color: #efefef;
    min-height: 100%;
    height: fit-content;
}

.admin__header-block {
    @apply flex items-center pt-4 pb-3 pl-10 text-3xl space-x-10 bg-gray-300;

    span {
        @apply cursor-pointer;
    }
}
</style>
