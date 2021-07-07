<template>
    <div class="admin h-full">
        <colored-side-panel :links="links"></colored-side-panel>

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
import ColoredSidePanel from '../../components/sidePanels/ColoredSidePanel.vue';
import stringHelper from '~/helpers/stringHelper';

export default {
    name: 'Admin',

    setup() {
        const route = useRoute();
        const router = useRouter();

        const { upperFirstLetter } = stringHelper();

        /** @member {UrlLink[]} links */
        const links = [
            {
                name: 'Chess rules',
                icon: 'feed',
                path: { name: 'adminChessRules' },
            },
            {
                name: 'Chess rule names',
                icon: 'list',
                path: { name: 'adminChessRuleNames' },
            },
            {
                name: 'Users',
                icon: 'group',
                path: { name: 'adminUsers' },
            },
            {
                name: 'Games',
                icon: 'videogame_asset',
                path: { name: 'adminGames' },
            },
            {
                name: 'Websockets',
                icon: 'sync_alt',
                path: { name: 'adminWebsockets' },
            },
        ];

        /** @property {UrlLink|undefined} currentLink.value */
        const currentLink = computed(() => {
            try {
                return links.find((link) => route.path === router.resolve(link.path).path);
            } catch (e) {
                return undefined;
            }
        });

        const getPageName = computed(() => (currentLink.value !== undefined
            ? currentLink.value.name
            : route.meta.name ?? 'Admin panel'
        ));

        return {
            links,
            router,
            upperFirstLetter,
            getPageName,
        };
    },

    components: { ColoredSidePanel },
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
