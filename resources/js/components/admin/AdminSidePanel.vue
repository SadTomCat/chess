<template>
    <div class="admin__side-panel">
        <ul class="admin-side-panel__list">
            <li class="admin-side-panel__list-item" v-for="(obj, name) in links" :key="name">
                <span class="material-icons">{{ obj.icon }}</span>
                <router-link :to="obj.link">{{ name }}</router-link>
            </li>
        </ul>
    </div>
</template>

<script>
import { reactive } from 'vue';

export default {
    name: 'AdminSidePanel',

    setup() {
        const links = reactive({
            'Chess rules': {
                icon: 'feed',
                link: '/admin/chess-rules',
            },
            'Chess rules category': {
                icon: 'list',
                link: '/admin/chess-rules-categories',
            },
            Users: {
                icon: 'group',
                link: '/admin/users',
            },
            Games: {
                icon: 'videogame_asset',
                link: '/admin/games',
            },
        });

        return {
            links,
        };
    },
};
</script>

<style lang="scss">
.admin__side-panel {
    @apply flex h-full max-h-screen fixed w-72 py-12 bg-gradient-to-t from-yellow-600 to-pink-600;
}

.admin-side-panel__list {
    @apply w-full text-white text-lg pl-4;

    &-item {
        @apply flex  py-3;

        a {
          @apply pl-2;
        }
    }
}
</style>
